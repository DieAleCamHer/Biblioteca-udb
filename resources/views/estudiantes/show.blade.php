@extends('layouts.app')

@section('title', 'Detalle del Estudiante')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-person-fill"></i> Información del Estudiante</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $estudiante->id }}</td>
                        </tr>
                        <tr>
                            <th>Carnet:</th>
                            <td><code class="fs-5">{{ $estudiante->carnet }}</code></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><strong>{{ $estudiante->nombre }}</strong></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $estudiante->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono:</th>
                            <td>{{ $estudiante->telefono ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($estudiante->activo)
                                    <span class="badge bg-success fs-6">Activo</span>
                                @else
                                    <span class="badge bg-danger fs-6">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Registro:</th>
                            <td>{{ $estudiante->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $estudiante->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                <div class="d-flex gap-2">
                    <a href="{{ route('estudiantes.edit', $estudiante->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </a>
                    
                    @if($estudiante->activo)
                        @if($estudiante->puedeDesactivar())
                            <form action="{{ route('estudiantes.desactivar', $estudiante->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Desactivar este estudiante?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <i class="bi bi-x-circle-fill"></i> Desactivar
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled title="Tiene préstamos activos">
                                <i class="bi bi-x-circle-fill"></i> Desactivar (Bloqueado)
                            </button>
                        @endif
                    @else
                        <form action="{{ route('estudiantes.activar', $estudiante->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle-fill"></i> Activar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total de Préstamos:</strong>
                    <h3 class="text-primary">{{ $estudiante->prestamos->count() }}</h3>
                </div>
                <div class="mb-3">
                    <strong>Préstamos Activos:</strong>
                    <h3 class="text-warning">{{ $prestamosActivos->count() }}</h3>
                </div>
                <div>
                    <strong>Préstamos Devueltos:</strong>
                    <h3 class="text-success">{{ $estudiante->prestamos->where('estado', 'devuelto')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Historial de Préstamos --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Préstamos</h5>
            </div>
            <div class="card-body">
                @if($estudiante->prestamos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Libro</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Fecha Límite</th>
                                    <th>Fecha Devolución</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudiante->prestamos as $prestamo)
                                    <tr>
                                        <td>{{ $prestamo->id }}</td>
                                        <td>
                                            <strong>{{ $prestamo->libro->titulo }}</strong><br>
                                            <small class="text-muted">{{ $prestamo->libro->autor }}</small>
                                        </td>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Este estudiante no tiene préstamos registrados.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
