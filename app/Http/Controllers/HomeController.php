<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Categoria;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar el dashboard principal con estadísticas.
     */
    public function index()
    {
        $totalLibros = Libro::count();
        $totalCategorias = Categoria::count();
        $prestamosActivos = Prestamo::where('estado', 'activo')->count();
        $librosDisponibles = Libro::where('stock', '>', 0)->count();
        
        // Libros más prestados
        $librosMasPrestados = Libro::withCount('prestamos')
            ->orderBy('prestamos_count', 'desc')
            ->take(5)
            ->get();
        
        // Préstamos recientes
        $prestamosRecientes = Prestamo::with(['libro.categoria'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('welcome', compact(
            'totalLibros',
            'totalCategorias',
            'prestamosActivos',
            'librosDisponibles',
            'librosMasPrestados',
            'prestamosRecientes'
        ));
    }
}
