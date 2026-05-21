@extends('layouts.app')

@section('title', 'Registrar Préstamo')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle-fill"></i> Registrar Nuevo Préstamo</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('prestamos.store') }}" method="POST" id="formPrestamo">
                    @csrf

                    {{-- Seleccionar Libro --}}
                    <div class="mb-4">
                        <label for="libro_id" class="form-label">
                            Libro a Prestar <span class="text-danger">*</span>
                        </label>
                        <select name="libro_id" 
                                id="libro_id" 
                                class="form-select @error('libro_id') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un libro</option>
                            @foreach($libros as $libro)
                                <option value="{{ $libro->id }}" {{ old('libro_id') == $libro->id ? 'selected' : '' }}>
                                    {{ $libro->titulo }} - {{ $libro->autor }} 
                                    (Stock: {{ $libro->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('libro_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- Opción: Estudiante Existente o Nuevo --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Estudiante <span class="text-danger">*</span>
                        </label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" 
                                   class="btn-check" 
                                   name="opcion_estudiante" 
                                   id="opcion_existente" 
                                   value="existente" 
                                   {{ old('opcion_estudiante', 'existente') == 'existente' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="opcion_existente">
                                <i class="bi bi-person-check-fill"></i> Estudiante Registrado
                            </label>

                            <input type="radio" 
                                   class="btn-check" 
                                   name="opcion_estudiante" 
                                   id="opcion_nuevo" 
                                   value="nuevo"
                                   {{ old('opcion_estudiante') == 'nuevo' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="opcion_nuevo">
                                <i class="bi bi-person-plus-fill"></i> Nuevo Estudiante
                            </label>
                        </div>
                        @error('opcion_estudiante')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Panel: Estudiante Existente --}}
                    <div id="panel_existente" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                Seleccionar Estudiante Registrado
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="estudiante_id" class="form-label">Estudiante</label>
                                    <select name="estudiante_id" 
                                            id="estudiante_id" 
                                            class="form-select @error('estudiante_id') is-invalid @enderror">
                                        <option value="">Seleccione un estudiante</option>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->carnet }} - {{ $estudiante->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estudiante_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel: Nuevo Estudiante --}}
                    <div id="panel_nuevo" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                Registrar Nuevo Estudiante
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_carnet" class="form-label">
                                            Carnet <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nuevo_carnet" 
                                               id="nuevo_carnet" 
                                               class="form-control @error('nuevo_carnet') is-invalid @enderror"
                                               value="{{ old('nuevo_carnet') }}"
                                               placeholder="Ej: CH252968"
                                               maxlength="8">
                                        <small class="form-text text-muted">
                                            Formato: 2 letras + 6 números (Ej: CH252968, MD259867)
                                        </small>
                                        @error('nuevo_carnet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_nombre" class="form-label">
                                            Nombre Completo <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nuevo_nombre" 
                                               id="nuevo_nombre" 
                                               class="form-control @error('nuevo_nombre') is-invalid @enderror"
                                               value="{{ old('nuevo_nombre') }}"
                                               maxlength="150">
                                        @error('nuevo_nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_email" class="form-label">Email (Opcional)</label>
                                        <input type="email" 
                                               name="nuevo_email" 
                                               id="nuevo_email" 
                                               class="form-control @error('nuevo_email') is-invalid @enderror"
                                               value="{{ old('nuevo_email') }}">
                                        @error('nuevo_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_telefono" class="form-label">Teléfono (Opcional)</label>
                                        <input type="text" 
                                               name="nuevo_telefono" 
                                               id="nuevo_telefono" 
                                               class="form-control @error('nuevo_telefono') is-invalid @enderror"
                                               value="{{ old('nuevo_telefono') }}"
                                               placeholder="Ej: 7890-1234">
                                        @error('nuevo_telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Información del Préstamo --}}
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Información:</strong>
                        <ul class="mb-0 mt-2">
                            <li>El préstamo se registrará con fecha de hoy</li>
                            <li>Fecha límite de devolución: <strong>{{ now()->addDays(7)->format('d/m/Y') }}</strong> (7 días)</li>
                            <li>El stock del libro se reducirá automáticamente</li>
                        </ul>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save-fill"></i> Registrar Préstamo
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
    // Mostrar/ocultar paneles según opción seleccionada
    document.addEventListener('DOMContentLoaded', function() {
        const opcionExistente = document.getElementById('opcion_existente');
        const opcionNuevo = document.getElementById('opcion_nuevo');
        const panelExistente = document.getElementById('panel_existente');
        const panelNuevo = document.getElementById('panel_nuevo');

        function togglePanels() {
            if (opcionExistente.checked) {
                panelExistente.style.display = 'block';
                panelNuevo.style.display = 'none';
            } else {
                panelExistente.style.display = 'none';
                panelNuevo.style.display = 'block';
            }
        }

        opcionExistente.addEventListener('change', togglePanels);
        opcionNuevo.addEventListener('change', togglePanels);

        // Inicializar al cargar
        togglePanels();

        // Validar formato de carnet en tiempo real
        const carnetInput = document.getElementById('nuevo_carnet');
        if (carnetInput) {
            carnetInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }
    });
</script>
@endsection
