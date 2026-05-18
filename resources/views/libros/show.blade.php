@extends('layouts.app')

@section('title', 'Detalle del Libro')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('libros.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-book-fill"></i> Información del Libro</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th width="30%">ID:</th>
                            <td>{{ $libro->id }}</td>
                        </tr>
                        <tr>
                            <th>Título:</th>
                            <td><strong>{{ $libro->titulo }}</strong></td>
                        </tr>
                        <tr>
                            <th>Autor:</th>
                            <td>{{ $libro->autor }}</td>
                        </tr>
                        <tr>
                            <th>ISBN:</th>
                            <td><code>{{ $libro->isbn }}</code></td>
                        </tr>
                        <tr>
                            <th>Año de Publicación:</th>
                            <td><strong>{{ $libro->anio_publicacion }}</strong></td>
                        </tr>
                        <tr>
                            <th>Categoría:</th>
                            <td>
                                <span class="badge bg-info">{{ $libro->categoria->nombre }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Stock Disponible:</th>
                            <td>
                                @if($libro->stock > 0)
                                    <span class="badge bg-success fs-6">{{ $libro->stock }} unidad(es)</span>
                                @else
                                    <span class="badge bg-danger fs-6">Sin stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Registro:</th>
                            <td>{{ $libro->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $libro->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </a>
                    
                    @if($libro->puedeEliminar())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash-fill"></i> Eliminar
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" disabled title="No se puede eliminar con préstamos activos">
                            <i class="bi bi-trash-fill"></i> Eliminar (Bloqueado)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total de Préstamos:</strong>
                    <h3 class="text-primary">{{ $libro->prestamos->count() }}</h3>
                </div>
                <div class="mb-3">
                    <strong>Préstamos Activos:</strong>
                    <h3 class="text-warning">{{ $prestamosActivos->count() }}</h3>
                </div>
                <div>
                    <strong>Préstamos Devueltos:</strong>
                    <h3 class="text-success">{{ $libro->prestamos->where('estado', 'devuelto')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Historial de Préstamos -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Préstamos</h5>
            </div>
            <div class="card-body">
                @if($libro->prestamos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Estudiante</th>
                                    <th>Carnet</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Fecha Límite</th>
                                    <th>Fecha Devolución</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($libro->prestamos as $prestamo)
                                    <tr>
                                        <td>{{ $prestamo->id }}</td>
                                        <td>{{ $prestamo->nombre_estudiante }}</td>
                                        <td><code>{{ $prestamo->carnet_estudiante }}</code></td>
                                        <td>{{ $prestamo->fecha_prestamo->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($prestamo->fecha_limite)
                                                {{ $prestamo->fecha_limite->format('d/m/Y') }}
                                            @else
                                                <em class="text-muted">N/A</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if($prestamo->fecha_devolucion)
                                                {{ $prestamo->fecha_devolucion->format('d/m/Y H:i') }}
                                            @else
                                                <em class="text-muted">Pendiente</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $prestamo->getColorBadge() }}">
                                                {{ $prestamo->getEstadoTexto() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Este libro no ha sido prestado aún.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
@if($libro->puedeEliminar())
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este libro?</p>
                <div class="alert alert-warning">
                    <strong>{{ $libro->titulo }}</strong><br>
                    <small>Esta acción no se puede deshacer.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
