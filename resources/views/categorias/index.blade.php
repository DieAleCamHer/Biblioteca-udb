@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1 class="display-6"><i class="bi bi-tags-fill"></i> Categorías de Libros</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Categoría
        </a>
    </div>
</div>

@if($categorias->count() > 0)
    <div class="row g-4">
        @foreach($categorias as $categoria)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-tag-fill text-primary"></i> {{ $categoria->nombre }}
                        </h5>
                        @if($categoria->descripcion)
                            <p class="card-text text-muted">{{ $categoria->descripcion }}</p>
                        @else
                            <p class="card-text text-muted fst-italic">Sin descripción</p>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-book"></i> {{ $categoria->libros_count }} libro(s)
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('libros.index', ['categoria_id' => $categoria->id]) }}" 
                               class="btn btn-sm btn-info flex-fill">
                                <i class="bi bi-list-ul"></i> Ver Libros
                            </a>
                            <a href="{{ route('categorias.edit', $categoria->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $categoria->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Confirmación de Eliminación -->
            <div class="modal fade" id="deleteModal{{ $categoria->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Está seguro que desea eliminar la siguiente categoría?</p>
                            <div class="alert alert-warning">
                                <strong>{{ $categoria->nombre }}</strong>
                            </div>
                            @if($categoria->libros_count > 0)
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <strong>Advertencia:</strong> Esta categoría tiene {{ $categoria->libros_count }} libro(s) asociado(s) y no puede ser eliminada.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            @if($categoria->libros_count === 0)
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $categorias->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> No hay categorías registradas aún. 
        <a href="{{ route('categorias.create') }}" class="alert-link">Haga clic aquí para agregar la primera.</a>
    </div>
@endif
@endsection
