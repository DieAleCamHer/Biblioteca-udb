<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with(['libro.categoria', 'estudiante'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('prestamos.index', compact('prestamos'));
    }

    public function create(Request $request)
    {
        // BÚSQUEDA DE LIBROS
        $librosQuery = Libro::activos()->where('stock', '>', 0)->with('categoria');
        
        if ($request->filled('buscar_libro')) {
            $buscar = $request->buscar_libro;
            $librosQuery->where(function($q) use ($buscar) {
                $q->where('isbn', 'LIKE', "%{$buscar}%")
                  ->orWhere('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('autor', 'LIKE', "%{$buscar}%")
                  ->orWhereHas('categoria', function($query) use ($buscar) {
                      $query->where('nombre', 'LIKE', "%{$buscar}%");
                  });
            });
        }
        
        $libros = $librosQuery->orderBy('titulo')->get();

        // BÚSQUEDA DE ESTUDIANTES
        $estudiantesQuery = Estudiante::activos();
        
        if ($request->filled('buscar_estudiante')) {
            $buscar = $request->buscar_estudiante;
            $estudiantesQuery->where(function($q) use ($buscar) {
                $q->where('carnet', 'LIKE', "%{$buscar}%")
                  ->orWhere('nombre', 'LIKE', "%{$buscar}%");
            });
        }
        
        $estudiantes = $estudiantesQuery->orderBy('nombre')->get();

        return view('prestamos.create', compact('libros', 'estudiantes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'opcion_estudiante' => 'required|in:existente,nuevo',
            'estudiante_id' => 'required_if:opcion_estudiante,existente|nullable|exists:estudiantes,id',
            'nuevo_carnet' => [
                'required_if:opcion_estudiante,nuevo',
                'nullable',
                'regex:/^[A-Z]{2}\d{6}$/',
                'unique:estudiantes,carnet',
            ],
            'nuevo_nombre' => [
                'required_if:opcion_estudiante,nuevo',
                'nullable',
                'string',
                'max:150',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'nuevo_email' => 'nullable|email:rfc,dns|max:100',
            'nuevo_telefono' => [
                'nullable',
                'string',
                'regex:/^\d{4}-\d{4}$/',
            ],
        ], [
            'libro_id.required' => 'Debe seleccionar un libro.',
            'libro_id.exists' => 'El libro seleccionado no existe.',
            'opcion_estudiante.required' => 'Debe seleccionar una opción de estudiante.',
            'estudiante_id.required_if' => 'Debe seleccionar un estudiante.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'nuevo_carnet.required_if' => 'El carnet es obligatorio.',
            'nuevo_carnet.regex' => 'El carnet debe tener el formato: XX123456.',
            'nuevo_carnet.unique' => 'Este carnet ya está registrado.',
            'nuevo_nombre.required_if' => 'El nombre es obligatorio.',
            'nuevo_nombre.regex' => 'El nombre solo puede contener letras.',
            'nuevo_email.email' => 'El email debe ser válido.',
            'nuevo_telefono.regex' => 'El teléfono debe tener el formato: XXXX-XXXX.',
        ]);

        DB::beginTransaction();

        try {
            // Verificar libro
            $libro = Libro::findOrFail($request->libro_id);

            if (!$libro->estaActivo()) {
                return back()
                    ->withErrors(['libro_id' => 'El libro seleccionado está desactivado.'])
                    ->withInput();
            }

            if (!$libro->estaDisponible()) {
                return back()
                    ->withErrors(['libro_id' => 'El libro no tiene stock disponible.'])
                    ->withInput();
            }

            // Procesar estudiante
            if ($request->opcion_estudiante === 'nuevo') {
                $estudiante = Estudiante::create([
                    'carnet' => strtoupper($request->nuevo_carnet),
                    'nombre' => $request->nuevo_nombre,
                    'email' => $request->nuevo_email,
                    'telefono' => $request->nuevo_telefono,
                    'activo' => true,
                ]);
                
                $estudianteId = $estudiante->id;
                $nombreEstudiante = $estudiante->nombre;
                $carnetEstudiante = $estudiante->carnet;
            } else {
                $estudiante = Estudiante::findOrFail($request->estudiante_id);
                
                if (!$estudiante->estaActivo()) {
                    return back()
                        ->withErrors(['estudiante_id' => 'El estudiante está desactivado.'])
                        ->withInput();
                }
                
                $estudianteId = $estudiante->id;
                $nombreEstudiante = $estudiante->nombre;
                $carnetEstudiante = $estudiante->carnet;
            }

            // Crear préstamo
            $fechaPrestamo = Carbon::now();
            $fechaLimite = Carbon::now()->addDays(7);

            $prestamo = Prestamo::create([
                'libro_id' => $libro->id,
                'estudiante_id' => $estudianteId,
                'nombre_estudiante' => $nombreEstudiante,
                'carnet_estudiante' => $carnetEstudiante,
                'fecha_prestamo' => $fechaPrestamo,
                'fecha_limite' => $fechaLimite,
                'estado' => 'activo',
            ]);

            // Reducir stock
            $libro->decrement('stock');

            DB::commit();

            return redirect()
                ->route('prestamos.index')
                ->with('success', 'Préstamo registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al registrar el préstamo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'activo') {
            return back()
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        DB::beginTransaction();

        try {
            $fechaDevolucion = Carbon::now();
            
            $diasRetraso = 0;
            $tieneRetraso = false;

            if ($fechaDevolucion->greaterThan($prestamo->fecha_limite)) {
                $diasRetraso = $fechaDevolucion->diffInDays($prestamo->fecha_limite);
                $tieneRetraso = true;
            }

            $prestamo->update([
                'fecha_devolucion' => $fechaDevolucion,
                'dias_retraso' => $diasRetraso,
                'tiene_retraso' => $tieneRetraso,
                'estado' => 'devuelto',
            ]);

            $prestamo->libro->increment('stock');

            DB::commit();

            $mensaje = $tieneRetraso 
                ? "Préstamo devuelto con {$diasRetraso} día(s) de retraso."
                : 'Préstamo devuelto a tiempo.';

            return redirect()
                ->route('prestamos.index')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al registrar la devolución.');
        }
    }
}
