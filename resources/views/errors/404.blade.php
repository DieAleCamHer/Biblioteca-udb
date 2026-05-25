@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-template py-5">
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="mb-4">Página no encontrada</h2>
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    El recurso que estás buscando no existe o ha sido eliminado.
                </div>
                <p class="text-muted mb-4">
                    Esto puede ocurrir si:
                </p>
                <ul class="list-unstyled text-muted mb-4">
                    <li>El ID en la URL no es válido</li>
                    <li>El registro fue eliminado previamente</li>
                    <li>La URL fue escrita incorrectamente</li>
                </ul>
                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2">
                        <i class="bi bi-house-door-fill"></i> Ir al Inicio
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Volver Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection