<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use App\Models\Libro;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        $query = Libro::with('categoria');

        // Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtrar por ISBN
        if ($request->filled('isbn')) {
            $query->where('isbn', 'LIKE', '%' . $request->isbn . '%');
        }

        // Filtrar por título
        if ($request->filled('titulo')) {
            $query->where('titulo', 'LIKE', '%' . $request->titulo . '%');
        }

        // Filtrar por autor
        if ($request->filled('autor')) {
            $query->where('autor', 'LIKE', '%' . $request->autor . '%');
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            if ($request->estado === 'activos') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('activo', false);
            }
        }

        $libros = $query->orderBy('titulo')->paginate(10);
        $categorias = Categoria::all();

        $categoriaSeleccionada = null;
        if ($request->filled('categoria_id')) {
            $categoriaSeleccionada = Categoria::find($request->categoria_id);
        }

        return view('libros.index', compact('libros', 'categorias', 'categoriaSeleccionada'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('libros.create', compact('categorias'));
    }

    public function store(StoreLibroRequest $request)
    {
        $libro = Libro::create($request->validated());

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    public function show(Libro $libro)
    {
        $libro->load(['categoria', 'prestamos.estudiante']);
        $prestamosActivos = $libro->prestamosActivos()->with('estudiante')->get();

        return view('libros.show', compact('libro', 'prestamosActivos'));
    }

    public function edit(Libro $libro)
    {
        $categorias = Categoria::all();
        return view('libros.edit', compact('libro', 'categorias'));
    }

    public function update(UpdateLibroRequest $request, Libro $libro)
    {
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

    public function destroy(Libro $libro)
    {
        if ($libro->prestamos()->count() > 0) {
            return back()
                ->with('error', 'No se puede eliminar el libro porque tiene préstamos registrados.');
        }

        $libro->delete();

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }

    public function desactivar(Libro $libro)
    {
        if (!$libro->puedeDesactivar()) {
            $cantidad = $libro->prestamosActivos()->count();
            
            return redirect()
                ->route('libros.index')
                ->with('error', "No se puede desactivar el libro porque tiene {$cantidad} préstamo(s) activo(s).");
        }

        $libro->desactivar();

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro desactivado exitosamente.');
    }

    public function activar(Libro $libro)
    {
        $libro->activar();

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro activado exitosamente.');
    }
}
