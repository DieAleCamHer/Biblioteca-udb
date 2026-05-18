@extends('layouts.app')

@section('title', 'Listado de Libros')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1>
            <i class="bi bi-book-fill"></i> 
            @if($categoriaSeleccionada)
                Libros - {{ $categoriaSeleccionada->nombre }}
            @else
                Listado de Libros
            @endif
        </h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('libros.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Nuevo Libro
        </a>
    </div>
</div>

<!-- Filtro por Categoría -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('libros.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="categoria_id" class="form-label">Filtrar por Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" 
                                {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill"></i> Filtrar
                </button>
                @if(request('categoria_id'))
                    <a href="{{ route('libros.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-x-circle"></i> Limpiar Filtro
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($libros->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Año</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($libros as $libro)
                            <tr>
                                <td>{{ $libro->id }}</td>
                                <td><strong>{{ $libro->titulo }}</strong></td>
                                <td>{{ $libro->autor }}</td>
                                <td><code>{{ $libro->isbn }}</code></td>
                                <td>{{ $libro->anio_publicacion }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $libro->categoria->nombre }}</span>
                                </td>
                                <td>
                                    @if($libro->stock > 0)
                                        <span class="badge bg-success">{{ $libro->stock }} disponible(s)</span>
                                    @else
                                        <span class="badge bg-danger">Sin stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('libros.show', $libro->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('libros.edit', $libro->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $libro->id }}"
                                                title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-3">
        {{ $libros->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> 
        @if($categoriaSeleccionada)
            No hay libros en la categoría "{{ $categoriaSeleccionada->nombre }}". 
            <a href="{{ route('libros.index') }}" class="alert-link">Ver todos los libros</a>
        @else
            No hay libros registrados aún. 
            <a href="{{ route('libros.create') }}" class="alert-link">Haga clic aquí para agregar el primero.</a>
        @endif
    </div>
@endif

<!-- MODALS FUERA DEL FOREACH -->
@foreach($libros as $libro)
<div class="modal fade" id="deleteModal{{ $libro->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $libro->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel{{ $libro->id }}">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar el siguiente libro?</p>
                <div class="alert alert-warning">
                    <strong>{{ $libro->titulo }}</strong><br>
                    <small>Autor: {{ $libro->autor }}</small>
                </div>
                @if($libro->prestamosActivos()->count() > 0)
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Advertencia:</strong> Este libro tiene {{ $libro->prestamosActivos()->count() }} préstamo(s) activo(s) y no puede ser eliminado.
                    </div>
                @else
                    <p class="text-muted">
                        <i class="bi bi-info-circle-fill"></i> Esta acción no se puede deshacer.
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                @if($libro->prestamosActivos()->count() === 0)
                    <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash-fill"></i> Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection