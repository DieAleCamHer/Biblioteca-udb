<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\EstudianteController;
use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de Libros
Route::resource('libros', LibroController::class);
Route::post('libros/{libro}/desactivar', [LibroController::class, 'desactivar'])->name('libros.desactivar');
Route::post('libros/{libro}/activar', [LibroController::class, 'activar'])->name('libros.activar');

// Rutas de Categorías
Route::resource('categorias', CategoriaController::class);

// Rutas de Estudiantes
Route::resource('estudiantes', EstudianteController::class);
Route::post('estudiantes/{estudiante}/desactivar', [EstudianteController::class, 'desactivar'])->name('estudiantes.desactivar');
Route::post('estudiantes/{estudiante}/activar', [EstudianteController::class, 'activar'])->name('estudiantes.activar');

// Rutas de Préstamos
Route::resource('prestamos', PrestamoController::class)->except(['edit', 'update', 'destroy']);
Route::post('prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
