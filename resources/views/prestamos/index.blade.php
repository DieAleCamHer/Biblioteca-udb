@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="bi bi-arrow-left-right"></i> Gestión de Préstamos</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('prestamos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Registrar Nuevo Préstamo
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <button class="btn btn-outline-warning w-100" onclick="filterPrestamos('activo')">
                    <i class="bi bi-hourglass-split"></i> Solo Activos
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-success w-100" onclick="filterPrestamos('devuelto')">
                    <i class="bi bi-check-circle-fill"></i> Solo Devueltos
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100" onclick="filterPrestamos('todos')">
                    <i class="bi bi-list"></i> Mostrar Todos
                </button>
            </div>
        </div>
    </div>
</div>

@if($prestamos->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaPrestamos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Estudiante</th>
                            <th>Carnet</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Límite</th>
                            <th>Fecha Devolución</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestamos as $prestamo)
                            <tr data-estado="{{ $prestamo->estado }}">
                                <td>{{ $prestamo->id }}</td>
                                <td>
                                    <strong>{{ $prestamo->libro->titulo }}</strong><br>
                                    <small class="text-muted">
                                        {{ $prestamo->libro->autor }}
                                        @if($prestamo->libro->anio_publicacion)
                                            ({{ $prestamo->libro->anio_publicacion }})
                                        @endif
                                    </small>
                                </td>
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
                                        @if($prestamo->tiene_retraso)
                                            <br><small class="text-danger">
                                                <i class="bi bi-exclamation-triangle-fill"></i> 
                                                {{ $prestamo->dias_retraso }} día(s) de retraso
                                            </small>
                                        @endif
                                    @else
                                        <em class="text-muted">Pendiente</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $prestamo->getColorBadge() }}">
                                        {{ $prestamo->getEstadoTexto() }}
                                    </span>
                                </td>
                                <td>
                                    @if($prestamo->estado === 'activo')
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#devolverModal{{ $prestamo->id }}">
                                            <i class="bi bi-arrow-return-left"></i> Devolver
                                        </button>
                                    @else
                                        <span class="text-success">
                                            <i class="bi bi-check-circle-fill"></i> Completado
                                        </span>
                                    @endif
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
        {{ $prestamos->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> No hay préstamos registrados aún. 
        <a href="{{ route('prestamos.create') }}" class="alert-link">Haga clic aquí para registrar el primero.</a>
    </div>
@endif

<!-- MODALS FUERA DEL FOREACH -->
@foreach($prestamos as $prestamo)
    @if($prestamo->estado === 'activo')
    <div class="modal fade" id="devolverModal{{ $prestamo->id }}" tabindex="-1" aria-labelledby="devolverModalLabel{{ $prestamo->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="devolverModalLabel{{ $prestamo->id }}">
                        <i class="bi bi-arrow-return-left"></i> Confirmar Devolución
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Confirma la devolución del siguiente libro?</p>
                    <div class="alert alert-info">
                        <strong>Libro:</strong> {{ $prestamo->libro->titulo }}
                        @if($prestamo->libro->anio_publicacion)
                            ({{ $prestamo->libro->anio_publicacion }})
                        @endif
                        <br>
                        <strong>Estudiante:</strong> {{ $prestamo->nombre_estudiante }}<br>
                        <strong>Fecha de préstamo:</strong> {{ $prestamo->fecha_prestamo->format('d/m/Y H:i') }}<br>
                        @if($prestamo->fecha_limite)
                            <strong>Fecha límite:</strong> {{ $prestamo->fecha_limite->format('d/m/Y') }}
                        @endif
                    </div>
                    <p class="text-muted">
                        <i class="bi bi-info-circle-fill"></i> El stock del libro se incrementará automáticamente.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill"></i> Confirmar Devolución
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection

@section('scripts')
<script>
    function filterPrestamos(estado) {
        const filas = document.querySelectorAll('#tablaPrestamos tbody tr');
        
        filas.forEach(fila => {
            const estadoFila = fila.getAttribute('data-estado');
            
            if (estado === 'todos') {
                fila.style.display = '';
            } else if (estadoFila === estado) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }
</script>
@endsection
