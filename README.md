# 📚 Sistema de Gestión de Biblioteca UDB

Sistema web desarrollado con **Laravel 11** para la gestión de biblioteca universitaria.

## 🚀 Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.6
- MySQL/MariaDB >= 5.7
- Apache/Nginx (XAMPP recomendado)

## 📥 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/TU_USUARIO/biblioteca-udb.git
cd biblioteca-udb
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar archivo .env
```bash
cp .env.example .env
```

Edita `.env` con tus datos de base de datos:
```env
DB_DATABASE=biblioteca_udb
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generar clave de aplicación
```bash
php artisan key:generate
```

### 5. Crear base de datos
Crea una base de datos llamada `biblioteca_udb` en phpMyAdmin.

### 6. Ejecutar migraciones
```bash
php artisan migrate
```

### 7. (Opcional) Poblar datos de prueba
```bash
php artisan db:seed
```

### 8. Iniciar servidor
```bash
php artisan serve
```

Accede a: http://localhost:8000

## 📋 Funcionalidades

- ✅ CRUD completo de Libros
- ✅ CRUD completo de Categorías
- ✅ Gestión de Préstamos
- ✅ Control de stock automático
- ✅ Validaciones robustas
- ✅ Control de retrasos en devoluciones
