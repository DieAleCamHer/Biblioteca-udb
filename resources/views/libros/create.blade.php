@extends('layouts.app')

@section('title', 'Agregar Nuevo Libro')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle-fill"></i> Agregar Nuevo Libro</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('libros.store') }}" method="POST">
                    @csrf

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">
                            Categoría <span class="text-danger">*</span>
                        </label>
                        <select name="categoria_id" 
                                id="categoria_id" 
                                class="form-select @error('categoria_id') is-invalid @enderror"
                                required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Título -->
                    <div class="mb-3">
                        <label for="titulo" class="form-label">
                            Título del Libro <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               class="form-control @error('titulo') is-invalid @enderror"
                               value="{{ old('titulo') }}"
                               maxlength="200"
                               required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Autor -->
                    <div class="mb-3">
                        <label for="autor" class="form-label">
                            Autor <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="autor" 
                               id="autor" 
                               class="form-control @error('autor') is-invalid @enderror"
                               value="{{ old('autor') }}"
                               maxlength="150"
                               required>
                        @error('autor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- ISBN -->
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">
                                ISBN (13 dígitos) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="isbn" 
                                   id="isbn" 
                                   class="form-control @error('isbn') is-invalid @enderror"
                                   value="{{ old('isbn') }}"
                                   maxlength="13"
                                   pattern="[0-9]{13}"
                                   placeholder="Ej: 9788420412146"
                                   required>
                            <small class="form-text text-muted">Solo números, exactamente 13 dígitos</small>
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Año de Publicación -->
                        <div class="col-md-6 mb-3">
                            <label for="anio_publicacion" class="form-label">
                                Año de Publicación <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="anio_publicacion" 
                                   id="anio_publicacion" 
                                   class="form-control @error('anio_publicacion') is-invalid @enderror"
                                   value="{{ old('anio_publicacion') }}"
                                   min="1450"
                                   max="{{ date('Y') }}"
                                   placeholder="Ej: 2020"
                                   required>
                            <small class="form-text text-muted">Entre 1450 y {{ date('Y') }}</small>
                            @error('anio_publicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">
                            Stock Inicial <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', 1) }}"
                               min="0"
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('libros.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill"></i> Guardar Libro
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
    // Validación de ISBN en tiempo real
    document.getElementById('isbn').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
    });
    
    // Validación de año de publicación
    document.getElementById('anio_publicacion').addEventListener('input', function(e) {
        const anio = parseInt(this.value);
        const anioActual = {{ date('Y') }};
        
        if (anio > anioActual) {
            this.value = anioActual;
        }
        if (anio < 1450 && this.value.length === 4) {
            this.value = 1450;
        }
    });
</script>
@endsection
