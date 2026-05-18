<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrestamoRequest;
use App\Models\Prestamo;
use App\Models\Libro;
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
        $prestamos = Prestamo::with(['libro.categoria'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('prestamos.index', compact('prestamos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo mostrar libros con stock disponible
        $libros = Libro::where('stock', '>', 0)
            ->with('categoria')
            ->orderBy('titulo')
            ->get();
        
        return view('prestamos.create', compact('libros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrestamoRequest $request)
    {
        // Usar transacción para garantizar integridad
        DB::beginTransaction();
        
        try {
            $libro = Libro::findOrFail($request->libro_id);
            
            // VALIDACIÓN CRÍTICA: Verificar stock disponible
            if (!$libro->estaDisponible()) {
                DB::rollBack();
                return back()
                    ->withErrors(['libro_id' => 'El libro seleccionado no tiene stock disponible.'])
                    ->withInput();
            }
            
            // Calcular fecha límite (7 días después del préstamo)
            $fechaPrestamo = now();
            $fechaLimite = Carbon::parse($fechaPrestamo)->addDays(7);
            
            // Crear el préstamo
            $prestamo = Prestamo::create([
                'libro_id' => $request->libro_id,
                'nombre_estudiante' => $request->nombre_estudiante,
                'carnet_estudiante' => strtoupper($request->carnet_estudiante), // Convertir a mayúsculas
                'fecha_prestamo' => $fechaPrestamo,
                'fecha_limite' => $fechaLimite,
                'estado' => 'activo',
                'dias_retraso' => 0,
                'tiene_retraso' => false,
            ]);
            
            // Decrementar el stock del libro
            $libro->decrement('stock');
            
            DB::commit();
            
            return redirect()
                ->route('prestamos.index')
                ->with('success', "Préstamo registrado exitosamente. Fecha límite de devolución: {$fechaLimite->format('d/m/Y')}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Error al procesar el préstamo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Devolver un libro prestado.
     */
    public function devolver(string $id)
    {
        DB::beginTransaction();
        
        try {
            $prestamo = Prestamo::with('libro')->findOrFail($id);
            
            // Validar que el préstamo esté activo
            if (!$prestamo->estaActivo()) {
                DB::rollBack();
                return back()
                    ->withErrors(['error' => 'Este préstamo ya fue devuelto anteriormente.']);
            }
            
            // Registrar fecha y hora actual de devolución
            $fechaDevolucion = now();
            
            // Calcular días de retraso
            $diasRetraso = 0;
            $tieneRetraso = false;
            
            if ($fechaDevolucion->greaterThan($prestamo->fecha_limite)) {
                $diasRetraso = $fechaDevolucion->diffInDays($prestamo->fecha_limite);
                $tieneRetraso = true;
            }
            
            // Marcar como devuelto
            $prestamo->update([
                'estado' => 'devuelto',
                'fecha_devolucion' => $fechaDevolucion,
                'dias_retraso' => $diasRetraso,
                'tiene_retraso' => $tieneRetraso,
            ]);
            
            // Incrementar el stock del libro
            $prestamo->libro->increment('stock');
            
            DB::commit();
            
            $mensaje = 'Libro devuelto exitosamente.';
            
            if ($tieneRetraso) {
                $mensaje .= " El libro se devolvió con {$diasRetraso} día(s) de retraso.";
                return redirect()
                    ->route('prestamos.index')
                    ->with('warning', $mensaje);
            }
            
            $mensaje .= " El libro se devolvió a tiempo.";
            return redirect()
                ->route('prestamos.index')
                ->with('success', $mensaje);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Error al procesar la devolución: ' . $e->getMessage()]);
        }
    }
}
