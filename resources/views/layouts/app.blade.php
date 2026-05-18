<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca UDB')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #0242A2;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #D9A711;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-bg: #f8f9fa;
        }

        .bg-card-dark {
            background-color: #0242A2 !important;
        }
        .bg-mustard {
            background-color: #D9A711 !important;
            color: #fff !important;
        }
        .bg-card-dark {
            background-color: #0242A2 !important;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 3px solid #0a58ca;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            transition: background-color 0.2s;
            border-radius: 4px;
            margin: 0 4px;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
        }
        
        main {
            flex: 1;
            background-color: var(--light-bg);
        }
        
        footer {
            background-color: #0242A2;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            border-top: 3px solid var(--primary-color);
        }
        
        .card {
            
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .card-header {
            font-weight: 600;
            border-bottom: 2px solid rgba(0,0,0,0.125);
        }
        
        .btn {
            font-weight: 500;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: var(--dark-color);
        }
        
        .btn-warning:hover {
            background-color: #ffca2c;
            border-color: #ffc720;
            color: var(--dark-color);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
            color: var(--dark-color);
        }
        
        .btn-info:hover {
            background-color: #31d2f2;
            border-color: #25cff2;
            color: var(--dark-color);
            transform: translateY(-1px);
        }
        
        .table {
            background-color: white;
        }
        
        .table thead {
            background-color: var(--primary-color);
            color: white;
            border-bottom: 2px solid #0a58ca;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .badge {
            padding: 0.4em 0.8em;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.85em;
        }
        
        .alert {
            border-radius: 6px;
            border-left: 4px solid;
        }
        
        .alert-success {
            border-left-color: var(--success-color);
        }
        
        .alert-danger {
            border-left-color: var(--danger-color);
        }
        
        .alert-warning {
            border-left-color: var(--warning-color);
        }
        
        .alert-info {
            border-left-color: var(--info-color);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-link {
            color: var(--primary-color);
        }
        
        .page-link:hover {
            color: #0b5ed7;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .modal-header {
            border-bottom: 2px solid rgba(0,0,0,0.125);
        }
        
        .modal-footer {
            border-top: 2px solid rgba(0,0,0,0.125);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-book-half"></i> Biblioteca UDB
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door-fill"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('libros.*') ? 'active' : '' }}" href="{{ route('libros.index') }}">
                            <i class="bi bi-book-fill"></i> Libros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                            <i class="bi bi-tags-fill"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('prestamos.*') ? 'active' : '' }}" href="{{ route('prestamos.index') }}">
                            <i class="bi bi-arrow-left-right"></i> Préstamos
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="py-4">
        <div class="container">
            <!-- Mensajes Flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> 
                    <strong>Error</strong> - Por favor corrija los siguientes errores:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contenido de la página -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Biblioteca UDB - Sistema de Gestión de Biblioteca</p>
            <small>Universidad Don Bosco</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
