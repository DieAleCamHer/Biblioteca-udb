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
                <form action="{{ route('prestamos.store') }}" method="POST" id="formRegistrarPrestamo">
                    @csrf

                    {{-- SECCIÓN: BUSCAR LIBRO --}}
                    <div class="mb-4">
                        <label class="form-label">Libro a Prestar <span class="text-danger">*</span></label>
                        
                        {{-- Formulario de búsqueda (GET) --}}
                        <div class="input-group mb-3">
                            <input type="text" 
                                   id="inputBuscarLibro"
                                   class="form-control" 
                                   placeholder="Buscar por ISBN, título, autor o categoría"
                                   value="{{ request('buscar_libro') }}">
                            <button type="button" 
                                    class="btn btn-primary" 
                                    onclick="buscarLibro()">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                            @if(request('buscar_libro'))
                                <a href="{{ route('prestamos.create') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            @endif
                        </div>

                        {{-- Resultado de búsqueda o campo vacío --}}
                        @if(request('buscar_libro'))
                            @if($libros->count() > 0)
                                <div class="alert alert-success">
                                    <strong>Resultados encontrados:</strong> {{ $libros->count() }} libro(s)
                                </div>
                                <div class="list-group mb-3">
                                    @foreach($libros as $libro)
                                        <label class="list-group-item list-group-item-action">
                                            <input type="radio" 
                                                   name="libro_id" 
                                                   value="{{ $libro->id }}" 
                                                   {{ old('libro_id') == $libro->id ? 'checked' : '' }}
                                                   required>
                                            <strong>{{ $libro->titulo }}</strong> - {{ $libro->autor }}
                                            <br>
                                            <small class="text-muted">
                                                ISBN: {{ $libro->isbn }} | 
                                                Categoría: {{ $libro->categoria->nombre }} | 
                                                Stock: {{ $libro->stock }}
                                            </small>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    No se encontraron libros con: <strong>{{ request('buscar_libro') }}</strong>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Use el buscador para encontrar el libro
                            </div>
                        @endif

                        <input type="hidden" name="libro_id_hidden" id="libro_id_hidden" value="{{ old('libro_id') }}">
                        
                        @error('libro_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- SECCIÓN: OPCIÓN ESTUDIANTE --}}
                    <div class="mb-4">
                        <label class="form-label">Estudiante <span class="text-danger">*</span></label>
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
                    </div>

                    {{-- PANEL: ESTUDIANTE EXISTENTE --}}
                    <div id="panel_existente" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">Seleccionar Estudiante</div>
                            <div class="card-body">
                                
                                {{-- Búsqueda de estudiante --}}
                                <div class="input-group mb-3">
                                    <input type="text" 
                                           id="inputBuscarEstudiante"
                                           class="form-control" 
                                           placeholder="Buscar por carnet o nombre"
                                           value="{{ request('buscar_estudiante') }}">
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            onclick="buscarEstudiante()">
                                        <i class="bi bi-search"></i> Buscar
                                    </button>
                                    @if(request('buscar_estudiante'))
                                        <a href="{{ route('prestamos.create', ['buscar_libro' => request('buscar_libro')]) }}" 
                                           class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Limpiar
                                        </a>
                                    @endif
                                </div>

                                {{-- Resultados --}}
                                @if(request('buscar_estudiante'))
                                    @if($estudiantes->count() > 0)
                                        <div class="alert alert-success">
                                            <strong>Resultados:</strong> {{ $estudiantes->count() }} estudiante(s)
                                        </div>
                                        <div class="list-group">
                                            @foreach($estudiantes as $estudiante)
                                                <label class="list-group-item list-group-item-action">
                                                    <input type="radio" 
                                                           name="estudiante_id" 
                                                           value="{{ $estudiante->id }}"
                                                           {{ old('estudiante_id') == $estudiante->id ? 'checked' : '' }}>
                                                    <strong>{{ $estudiante->nombre }}</strong>
                                                    <br>
                                                    <small class="text-muted">Carnet: {{ $estudiante->carnet }}</small>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            No se encontraron estudiantes con: <strong>{{ request('buscar_estudiante') }}</strong>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Use el buscador para encontrar al estudiante
                                    </div>
                                @endif

                                @error('estudiante_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- PANEL: NUEVO ESTUDIANTE --}}
                    <div id="panel_nuevo" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">Registrar Nuevo Estudiante</div>
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
                                               placeholder="CH252968" 
                                               maxlength="8">
                                        <small class="form-text text-muted">Formato: XX123456</small>
                                        @error('nuevo_carnet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_nombre" class="form-label">
                                            Nombre <span class="text-danger">*</span>
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
                                               placeholder="7890-1234" 
                                               maxlength="9">
                                        <small class="form-text text-muted">Formato: XXXX-XXXX</small>
                                        @error('nuevo_telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong><i class="bi bi-calendar-check"></i> Información:</strong> 
                        Fecha límite de devolución: {{ now()->addDays(7)->format('d/m/Y') }}
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
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
    // FUNCIÓN: Buscar Libro
    function buscarLibro() {
        const buscarLibro = document.getElementById('inputBuscarLibro').value;
        const buscarEstudiante = document.getElementById('inputBuscarEstudiante').value;
        
        let url = '{{ route("prestamos.create") }}?buscar_libro=' + encodeURIComponent(buscarLibro);
        
        if (buscarEstudiante) {
            url += '&buscar_estudiante=' + encodeURIComponent(buscarEstudiante);
        }
        
        window.location.href = url;
    }

    // FUNCIÓN: Buscar Estudiante
    function buscarEstudiante() {
        const buscarLibro = document.getElementById('inputBuscarLibro').value;
        const buscarEstudiante = document.getElementById('inputBuscarEstudiante').value;
        
        let url = '{{ route("prestamos.create") }}?';
        
        if (buscarLibro) {
            url += 'buscar_libro=' + encodeURIComponent(buscarLibro) + '&';
        }
        
        url += 'buscar_estudiante=' + encodeURIComponent(buscarEstudiante);
        
        window.location.href = url;
    }

    // FUNCIÓN: Enter en inputs
    document.getElementById('inputBuscarLibro')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarLibro();
        }
    });

    document.getElementById('inputBuscarEstudiante')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarEstudiante();
        }
    });

    // FUNCIÓN: Mostrar/ocultar paneles
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
        togglePanels();

        // Convertir carnet a mayúsculas
        const carnetInput = document.getElementById('nuevo_carnet');
        if (carnetInput) {
            carnetInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }
    });
</script>
@endsection
