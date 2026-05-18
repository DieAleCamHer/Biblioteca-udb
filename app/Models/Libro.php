<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'categoria_id',
        'titulo',
        'autor',
        'isbn',
        'anio_publicacion',
        'stock',
    ];

    /**
     * Relación: Un libro pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación: Un libro tiene muchos préstamos
     */
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    /**
     * Obtener cantidad de préstamos activos
     */
    public function prestamosActivos()
    {
        return $this->prestamos()->where('estado', 'activo');
    }

    /**
     * Verificar si el libro está disponible
     */
    public function estaDisponible()
    {
        return $this->stock > 0;
    }

    /**
     * Verificar si se puede eliminar (no tiene préstamos activos)
     */
    public function puedeEliminar()
    {
        return $this->prestamosActivos()->count() === 0;
    }
}
