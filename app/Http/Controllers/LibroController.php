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
        
        // Filtrar por categoría si se proporciona el parámetro
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        $libros = $query->paginate(10);
        
        // Obtener todas las categorías para el filtro
        $categorias = Categoria::all();
        
        // Categoría seleccionada (para mostrar en el título)
        $categoriaSeleccionada = null;
        if ($request->has('categoria_id')) {
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
        // La validación ya fue hecha por StoreLibroRequest
        $libro = Libro::create($request->validated());

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $libro = Libro::with(['categoria', 'prestamos'])->findOrFail($id);
        
        // Obtener préstamos activos
        $prestamosActivos = $libro->prestamosActivos()->get();
        
        return view('libros.show', compact('libro', 'prestamosActivos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $libro = Libro::findOrFail($id);
        $categorias = Categoria::all();
        
        return view('libros.edit', compact('libro', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibroRequest $request, string $id)
    {
        $libro = Libro::findOrFail($id);
        
        // Validar que si se reduce el stock, no haya más préstamos activos que el nuevo stock
        $newStock = $request->stock;
        $prestamosActivos = $libro->prestamosActivos()->count();
        
        if ($newStock < $prestamosActivos) {
            return back()
                ->withErrors(['stock' => "No se puede reducir el stock a {$newStock} porque hay {$prestamosActivos} préstamo(s) activo(s)."])
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
    public function destroy(string $id)
    {
        $libro = Libro::findOrFail($id);
        
        // VALIDACIÓN CRÍTICA: Verificar que no tenga préstamos activos
        if (!$libro->puedeEliminar()) {
            $cantidadPrestamos = $libro->prestamosActivos()->count();
            
            return back()
                ->withErrors(['delete' => "No se puede eliminar el libro porque tiene {$cantidadPrestamos} préstamo(s) activo(s). Primero debe esperar a que sean devueltos."])
                ->with('error', 'No se puede eliminar el libro con préstamos activos.');
        }
        
        $libro->delete();

        return redirect()
            ->route('libros.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }
}
