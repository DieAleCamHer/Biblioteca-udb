<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrestamoRequest;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prestamos = Prestamo::with(['libro.categoria', 'estudiante'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('prestamos.index', compact('prestamos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo libros activos y con stock
        $libros = Libro::activos()
            ->where('stock', '>', 0)
            ->orderBy('titulo')
            ->get();

        // Solo estudiantes activos
        $estudiantes = Estudiante::activos()
            ->orderBy('nombre')
            ->get();

        return view('prestamos.create', compact('libros', 'estudiantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'opcion_estudiante' => 'required|in:existente,nuevo',
            
            // Si selecciona estudiante existente
            'estudiante_id' => 'required_if:opcion_estudiante,existente|nullable|exists:estudiantes,id',
            
            // Si crea nuevo estudiante
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
            'nuevo_email' => 'nullable|email|max:100',
            'nuevo_telefono' => 'nullable|string|max:15',
        ], [
            'libro_id.required' => 'Debe seleccionar un libro.',
            'libro_id.exists' => 'El libro seleccionado no existe.',
            'opcion_estudiante.required' => 'Debe seleccionar una opción de estudiante.',
            'estudiante_id.required_if' => 'Debe seleccionar un estudiante.',
            'nuevo_carnet.required_if' => 'El carnet es obligatorio.',
            'nuevo_carnet.regex' => 'El carnet debe tener el formato: 2 letras + 6 números (Ej: CH252968).',
            'nuevo_carnet.unique' => 'Este carnet ya está registrado.',
            'nuevo_nombre.required_if' => 'El nombre es obligatorio.',
            'nuevo_nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        ]);

        DB::beginTransaction();

        try {
            $libro = Libro::findOrFail($request->libro_id);

            // Validar que el libro esté activo y disponible
            if (!$libro->estaActivo()) {
                return back()
                    ->withErrors(['libro_id' => 'El libro seleccionado está desactivado.'])
                    ->withInput();
            }

            if (!$libro->estaDisponible()) {
                return back()
                    ->withErrors(['libro_id' => 'El libro seleccionado no tiene stock disponible.'])
                    ->withInput();
            }

            // Crear o seleccionar estudiante
            if ($request->opcion_estudiante === 'nuevo') {
                // Crear nuevo estudiante
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
                // Usar estudiante existente
                $estudiante = Estudiante::findOrFail($request->estudiante_id);
                
                if (!$estudiante->estaActivo()) {
                    return back()
                        ->withErrors(['estudiante_id' => 'El estudiante seleccionado está desactivado.'])
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

    /**
     * Registrar devolución de préstamo
     */
    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'activo') {
            return back()
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        DB::beginTransaction();

        try {
            $fechaDevolucion = Carbon::now();
            
            // Calcular retraso
            $diasRetraso = 0;
            $tieneRetraso = false;

            if ($fechaDevolucion->greaterThan($prestamo->fecha_limite)) {
                $diasRetraso = $fechaDevolucion->diffInDays($prestamo->fecha_limite);
                $tieneRetraso = true;
            }

            // Actualizar préstamo
            $prestamo->update([
                'fecha_devolucion' => $fechaDevolucion,
                'dias_retraso' => $diasRetraso,
                'tiene_retraso' => $tieneRetraso,
                'estado' => 'devuelto',
            ]);

            // Incrementar stock
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
                ->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }
}
