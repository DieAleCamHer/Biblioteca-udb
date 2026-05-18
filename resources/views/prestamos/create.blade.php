@extends('layouts.app')

@section('title', 'Registrar Préstamo')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle-fill"></i> Registrar Nuevo Préstamo</h4>
            </div>
            <div class="card-body">
                @if($libros->count() === 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>No hay libros disponibles.</strong> 
                        Todos los libros están agotados o no hay libros registrados.
                        <br>
                        <a href="{{ route('libros.index') }}" class="alert-link">Ver listado de libros</a>
                    </div>
                @else
                    <!-- Información de fecha automática -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Información:</strong> El préstamo se registrará con la fecha y hora actual del sistema.
                        <br>
                        <strong>Fecha límite:</strong> Se calculará automáticamente 7 días después del préstamo.
                    </div>

                    <form action="{{ route('prestamos.store') }}" method="POST" id="formPrestamo">
                        @csrf

                        <!-- Libro -->
                        <div class="mb-3">
                            <label for="libro_id" class="form-label">
                                Seleccione el Libro <span class="text-danger">*</span>
                            </label>
                            <select name="libro_id" 
                                    id="libro_id" 
                                    class="form-select @error('libro_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Seleccione un libro --</option>
                                @foreach($libros as $libro)
                                    <option value="{{ $libro->id }}" 
                                            data-stock="{{ $libro->stock }}"
                                            {{ old('libro_id') == $libro->id ? 'selected' : '' }}>
                                        {{ $libro->titulo }} - {{ $libro->autor }} 
                                        (Stock: {{ $libro->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Solo se muestran libros con stock disponible.
                            </small>
                            @error('libro_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información del Stock -->
                        <div id="stockInfo" class="alert alert-info d-none">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Stock disponible:</strong> <span id="stockValue">-</span> unidad(es)
                        </div>

                        <!-- Nombre del Estudiante -->
                        <div class="mb-3">
                            <label for="nombre_estudiante" class="form-label">
                                Nombre del Estudiante <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre_estudiante" 
                                   id="nombre_estudiante" 
                                   class="form-control @error('nombre_estudiante') is-invalid @enderror"
                                   value="{{ old('nombre_estudiante') }}"
                                   maxlength="150"
                                   placeholder="Ej: Juan Pérez González"
                                   pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                   required>
                            <small class="form-text text-muted">
                                Solo letras y espacios (sin números).
                            </small>
                            @error('nombre_estudiante')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Carnet del Estudiante -->
                        <div class="mb-3">
                            <label for="carnet_estudiante" class="form-label">
                                Carnet del Estudiante <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="carnet_estudiante" 
                                   id="carnet_estudiante" 
                                   class="form-control @error('carnet_estudiante') is-invalid @enderror"
                                   value="{{ old('carnet_estudiante') }}"
                                   maxlength="8"
                                   placeholder="Ej: CH252968 o MD259867"
                                   pattern="[A-Z]{2}[0-9]{6}"
                                   style="text-transform: uppercase;"
                                   required>
                            <small class="form-text text-muted">
                                Formato: 2 letras mayúsculas + 6 números (Ej: CH252968, MD259867)
                            </small>
                            @error('carnet_estudiante')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información de Fechas (No editable, solo informativa) -->
                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bi bi-calendar-check-fill"></i> Información del Préstamo</h6>
                                    <p class="mb-1">
                                        <strong>Fecha y hora del préstamo:</strong> 
                                        <span id="fechaActual"></span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Fecha límite de devolución:</strong> 
                                        <span id="fechaLimite"></span>
                                        <small class="text-muted">(7 días después)</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success" id="btnSubmit">
                                <i class="bi bi-save-fill"></i> Registrar Préstamo
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mostrar fecha y hora actual
    function actualizarFechas() {
        const ahora = new Date();
        const fechaLimite = new Date(ahora);
        fechaLimite.setDate(fechaLimite.getDate() + 7);
        
        const opciones = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        
        document.getElementById('fechaActual').textContent = ahora.toLocaleDateString('es-ES', opciones);
        document.getElementById('fechaLimite').textContent = fechaLimite.toLocaleDateString('es-ES', {
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        });
    }
    
    actualizarFechas();
    setInterval(actualizarFechas, 60000); // Actualizar cada minuto

    // Mostrar stock del libro seleccionado
    document.getElementById('libro_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        const stockInfo = document.getElementById('stockInfo');
        const stockValue = document.getElementById('stockValue');
        const btnSubmit = document.getElementById('btnSubmit');
        
        if (this.value) {
            stockInfo.classList.remove('d-none');
            stockValue.textContent = stock;
            
            if (parseInt(stock) <= 0) {
                btnSubmit.disabled = true;
                stockInfo.classList.remove('alert-info');
                stockInfo.classList.add('alert-danger');
            } else {
                btnSubmit.disabled = false;
                stockInfo.classList.remove('alert-danger');
                stockInfo.classList.add('alert-info');
            }
        } else {
            stockInfo.classList.add('d-none');
            btnSubmit.disabled = false;
        }
    });

    // Validación de nombre (solo letras)
    document.getElementById('nombre_estudiante').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
    });

    // Validación y formato de carnet (2 letras + 6 números)
    document.getElementById('carnet_estudiante').addEventListener('input', function(e) {
        let valor = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // Limitar a 2 letras al inicio
        if (valor.length > 0) {
            let letras = valor.substring(0, 2).replace(/[^A-Z]/g, '');
            let numeros = valor.substring(2).replace(/[^0-9]/g, '').substring(0, 6);
            this.value = letras + numeros;
        } else {
            this.value = '';
        }
    });
</script>
@endsection
