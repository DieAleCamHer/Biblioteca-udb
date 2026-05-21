<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Estudiante::query();

        // Filtrar por estado
        if ($request->has('estado')) {
            if ($request->estado === 'activos') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('activo', false);
            }
        }

        // Buscar por nombre o carnet
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('carnet', 'LIKE', "%{$buscar}%");
            });
        }

        $estudiantes = $query->orderBy('nombre')->paginate(15);

        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('estudiantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstudianteRequest $request)
    {
        $estudiante = Estudiante::create($request->validated());

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        $estudiante->load(['prestamos.libro']);
        
        $prestamosActivos = $estudiante->prestamosActivos()->with('libro')->get();
        
        return view('estudiantes.show', compact('estudiante', 'prestamosActivos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante)
    {
        $estudiante->update($request->validated());

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        // Verificar si tiene préstamos
        if ($estudiante->prestamos()->count() > 0) {
            return back()
                ->with('error', 'No se puede eliminar el estudiante porque tiene préstamos registrados. Desactívalo en su lugar.');
        }

        $estudiante->delete();

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }

    /**
     * Desactivar estudiante (soft delete)
     */
    public function desactivar(Estudiante $estudiante)
    {
        if (!$estudiante->puedeDesactivar()) {
            $cantidad = $estudiante->prestamosActivos()->count();
            
            return back()
                ->with('error', "No se puede desactivar el estudiante porque tiene {$cantidad} préstamo(s) activo(s).");
        }

        $estudiante->desactivar();

        return back()
            ->with('success', 'Estudiante desactivado exitosamente.');
    }

    /**
     * Activar estudiante
     */
    public function activar(Estudiante $estudiante)
    {
        $estudiante->activar();

        return back()
            ->with('success', 'Estudiante activado exitosamente.');
    }
}
