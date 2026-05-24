@extends('layouts.app')

@section('title', 'Estudiantes')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="bi bi-people-fill"></i> Gestión de Estudiantes</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('estudiantes.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Registrar Nuevo Estudiante
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('estudiantes.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="buscar" 
                           class="form-control" 
                           placeholder="Buscar por nombre o carnet..."
                           value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activos" {{ request('estado') == 'activos' ? 'selected' : '' }}>
                            Solo Activos
                        </option>
                        <option value="inactivos" {{ request('estado') == 'inactivos' ? 'selected' : '' }}>
                            Solo Inactivos
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if($estudiantes->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Carnet</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->id }}</td>
                                <td><code>{{ $estudiante->carnet }}</code></td>
                                <td>
                                    <strong>{{ $estudiante->nombre }}</strong>
                                    @if($estudiante->prestamosActivos()->count() > 0)
                                        <br>
                                        <small class="text-warning">
                                            <i class="bi bi-hourglass-split"></i>
                                            {{ $estudiante->prestamosActivos()->count() }} préstamo(s) activo(s)
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $estudiante->email ?? '-' }}</td>
                                <td>{{ $estudiante->telefono ?? '-' }}</td>
                                <td>
                                    @if($estudiante->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('estudiantes.show', $estudiante->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalle">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        
                                        <a href="{{ route('estudiantes.edit', $estudiante->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        
                                        @if($estudiante->activo)
                                            <form action="{{ route('estudiantes.desactivar', $estudiante->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Desactivar este estudiante?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-secondary"
                                                        title="Desactivar">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('estudiantes.activar', $estudiante->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success"
                                                        title="Activar">
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
        {{ $estudiantes->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> No hay estudiantes registrados.
        <a href="{{ route('estudiantes.create') }}" class="alert-link">Registra el primero aquí.</a>
    </div>
@endif
@endsection
