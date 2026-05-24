@extends('layouts.app')

@section('title', 'Libros')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="bi bi-book-fill"></i> Gestión de Libros</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('libros.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Agregar Nuevo Libro
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('libros.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-2">
                    <select name="categoria_id" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="isbn" class="form-control" 
                           placeholder="ISBN" value="{{ request('isbn') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="titulo" class="form-control" 
                           placeholder="Título" value="{{ request('titulo') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="autor" class="form-control" 
                           placeholder="Autor" value="{{ request('autor') }}">
                </div>
                <div class="col-md-2">
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activos" {{ request('estado') == 'activos' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivos" {{ request('estado') == 'inactivos' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <a href="{{ route('libros.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Limpiar filtros
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if($categoriaSeleccionada)
    <div class="alert alert-info">
        Mostrando libros de: <strong>{{ $categoriaSeleccionada->nombre }}</strong>
    </div>
@endif

@if($libros->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Año</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($libros as $libro)
                            <tr>
                                <td>{{ $libro->id }}</td>
                                <td>
                                    <strong>{{ $libro->titulo }}</strong>
                                    @if($libro->prestamosActivos()->count() > 0)
                                        <br><small class="text-warning">
                                            {{ $libro->prestamosActivos()->count() }} préstamo(s)
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $libro->autor }}</td>
                                <td><span class="badge bg-info">{{ $libro->categoria->nombre }}</span></td>
                                <td>{{ $libro->anio_publicacion }}</td>
                                <td>
                                    @if($libro->stock > 0)
                                        <span class="badge bg-success">{{ $libro->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($libro->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('libros.show', $libro->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('libros.edit', $libro->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @if($libro->activo)
                                            <button type="button" class="btn btn-sm btn-secondary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#desactivarModal{{ $libro->id }}">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('libros.activar', $libro->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $libros->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info">
        No hay libros registrados.
    </div>
@endif

{{-- Modales de Confirmación --}}
@foreach($libros as $libro)
    @if($libro->activo)
    <div class="modal fade" id="desactivarModal{{ $libro->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Confirmar Desactivación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea desactivar el siguiente libro?</p>
                    <div class="alert alert-warning mb-0">
                        <strong>{{ $libro->titulo }}</strong><br>
                        <small>{{ $libro->autor }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('libros.desactivar', $libro->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Desactivar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
