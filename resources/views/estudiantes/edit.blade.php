@extends('layouts.app')

@section('title', 'Editar Estudiante')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-pencil-fill"></i> Editar Estudiante</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('estudiantes.update', $estudiante->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="carnet" class="form-label">Carnet <span class="text-danger">*</span></label>
                            <input type="text" name="carnet" id="carnet" 
                                   class="form-control @error('carnet') is-invalid @enderror"
                                   value="{{ old('carnet', $estudiante->carnet) }}" maxlength="8" required>
                            <small class="form-text text-muted">Formato: XX123456</small>
                            @error('carnet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $estudiante->nombre) }}" maxlength="150" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email (Opcional)</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $estudiante->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono (Opcional)</label>
                            <input type="text" name="telefono" id="telefono" 
                                   class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $estudiante->telefono) }}" 
                                   placeholder="7890-1234" maxlength="9">
                            <small class="form-text text-muted">Formato: XXXX-XXXX</small>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($estudiante->prestamosActivos()->count() > 0)
                        <div class="alert alert-warning">
                            Este estudiante tiene {{ $estudiante->prestamosActivos()->count() }} préstamo(s) activo(s).
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save-fill"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('carnet').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
</script>
@endsection
