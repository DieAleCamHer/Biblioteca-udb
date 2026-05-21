<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use App\Models\Libro;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Libro::with('categoria');

        // Filtrar por categoría
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar por estado
        if ($request->has('estado')) {
            if ($request->estado === 'activos') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('activo', false);
            }
        }

        $libros = $query->orderBy('titulo')->paginate(10);
        $categorias = Categoria::all();

        // Obtener categoría seleccionada
        $categoriaSeleccionada = null;
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $categoriaSeleccionada = Categoria::find($request->categoria_id);
        }

        return view('libros.index', compact('libros', 'categorias', 'categoriaSeleccionada'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('libros.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLibroRequest $request)
    {
        $libro = Libro::create($request->validated());

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Libro $libro)
    {
        $libro->load(['categoria', 'prestamos.estudiante']);
        $prestamosActivos = $libro->prestamosActivos()->with('estudiante')->get();

        return view('libros.show', compact('libro', 'prestamosActivos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Libro $libro)
    {
        $categorias = Categoria::all();
        return view('libros.edit', compact('libro', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibroRequest $request, Libro $libro)
    {
        // Validar que el stock no sea menor a los préstamos activos
        $newStock = $request->stock;
        $prestamosActivos = $libro->prestamosActivos()->count();

        if ($newStock < $prestamosActivos) {
            return back()
                ->withErrors([
                    'stock' => "No se puede reducir el stock a {$newStock} porque hay {$prestamosActivos} préstamo(s) activo(s)."
                ])
                ->withInput();
        }

        $libro->update($request->validated());

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Libro $libro)
    {
        // No permitir eliminación si tiene préstamos
        if ($libro->prestamos()->count() > 0) {
            return back()
                ->with('error', 'No se puede eliminar el libro porque tiene préstamos registrados. Desactívalo en su lugar.');
        }

        $libro->delete();

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }

    /**
     * Desactivar libro (soft delete)
     */
    public function desactivar(Libro $libro)
    {
        if (!$libro->puedeDesactivar()) {
            $cantidad = $libro->prestamosActivos()->count();
            
            return back()
                ->with('error', "No se puede desactivar el libro porque tiene {$cantidad} préstamo(s) activo(s).");
        }

        $libro->desactivar();

        return back()
            ->with('success', 'Libro desactivado exitosamente.');
    }

    /**
     * Activar libro
     */
    public function activar(Libro $libro)
    {
        $libro->activar();

        return back()
            ->with('success', 'Libro activado exitosamente.');
    }
}