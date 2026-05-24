<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'titulo',
        'autor',
        'isbn',
        'anio_publicacion',
        'stock',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    public function prestamosActivos()
    {
        return $this->prestamos()->where('estado', 'activo');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function estaActivo()
    {
        return $this->activo;
    }

    public function estaDisponible()
    {
        return $this->activo && $this->stock > 0;
    }

    public function puedeDesactivar()
    {
        return $this->prestamosActivos()->count() === 0;
    }

    public function puedeEliminar()
    {
        return $this->prestamos()->count() === 0;
    }

    public function activar()
    {
        $this->update(['activo' => true]);
    }

    public function desactivar()
    {
        if ($this->puedeDesactivar()) {
            $this->update(['activo' => false]);
            return true;
        }
        return false;
    }
}
