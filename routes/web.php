<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PrestamoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página principal (Dashboard)
Route::get('/', [HomeController::class, 'index'])->name('home');

// CRUD Completo de Libros (Resource Controller)
Route::resource('libros', LibroController::class);

// CRUD Completo de Categorías (Resource Controller)
Route::resource('categorias', CategoriaController::class)->except(['show']);

// Rutas de Préstamos
Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
Route::get('/prestamos/create', [PrestamoController::class, 'create'])->name('prestamos.create');
Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
Route::post('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
