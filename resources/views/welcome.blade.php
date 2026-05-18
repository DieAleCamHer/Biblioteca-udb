@extends('layouts.app')

@section('title', 'Inicio - Biblioteca UDB')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-4">
            <i class="bi bi-house-door"></i> Panel de Control
        </h1>
        <p class="lead">Bienvenido al Sistema de Gestión de Biblioteca UDB</p>
        <hr>
    </div>
</div>

<!-- Estadísticas en Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card card-stat border-primary shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-book fs-1 text-primary"></i>
                <h3 class="mt-3 mb-0">{{ $totalLibros }}</h3>
                <p class="text-muted mb-0">Total de Libros</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stat border-success shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 text-success"></i>
                <h3 class="mt-3 mb-0">{{ $librosDisponibles }}</h3>
                <p class="text-muted mb-0">Libros Disponibles</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stat border-warning shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-arrow-left-right fs-1 text-warning"></i>
                <h3 class="mt-3 mb-0">{{ $prestamosActivos }}</h3>
                <p class="text-muted mb-0">Préstamos Activos</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stat border-info shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 text-info"></i>
                <h3 class="mt-3 mb-0">{{ $totalCategorias }}</h3>
                <p class="text-muted mb-0">Categorías</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Libros más prestados -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-card-dark text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-star"></i> Top 5 Libros Más Prestados</h5>
            </div>
            <div class="card-body">
                @if($librosMasPrestados->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($librosMasPrestados as $libro)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $libro->titulo }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $libro->autor }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $libro->prestamos_count }} préstamos
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No hay datos de préstamos aún.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Préstamos recientes -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-clock-history"></i> Préstamos Recientes</h5>
            </div>
            <div class="card-body">
                @if($prestamosRecientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($prestamosRecientes as $prestamo)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $prestamo->libro->titulo }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $prestamo->nombre_estudiante }}</small>
                                    </div>
                                    <span class="badge bg-{{ $prestamo->estado === 'activo' ? 'warning' : 'success' }}">
                                        {{ ucfirst($prestamo->estado) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No hay préstamos registrados aún.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="row mt-5">
    <div class="col-12">
        <h3><i class="bi bi-lightning"></i> Accesos Rápidos</h3>
        <hr>
    </div>
    <div class="col-md-4 mb-3">
        <a href="{{ route('libros.create') }}" class="btn btn-lg btn-primary w-100">
            <i class="bi bi-plus-circle"></i> Agregar Nuevo Libro
        </a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="{{ route('prestamos.create') }}" class="btn btn-lg btn-success w-100">
            <i class="bi bi-arrow-right-circle"></i> Registrar Préstamo
        </a>
    </div>
    <div class="col-md-4 mb-3">
        <a href="{{ route('libros.index') }}" class="btn btn-lg btn-info w-100">
            <i class="bi bi-list-ul"></i> Ver Todos los Libros
        </a>
    </div>
</div>
@endsection
