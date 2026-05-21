<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'carnet',
        'nombre',
        'email',
        'telefono',
        'activo',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Un estudiante tiene muchos préstamos
     */
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    /**
     * Obtener préstamos activos
     */
    public function prestamosActivos()
    {
        return $this->prestamos()->where('estado', 'activo');
    }

    /**
     * Scope para filtrar solo estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Verificar si el estudiante está activo
     */
    public function estaActivo()
    {
        return $this->activo;
    }

    /**
     * Verificar si se puede desactivar (no tiene préstamos activos)
     */
    public function puedeDesactivar()
    {
        return $this->prestamosActivos()->count() === 0;
    }

    /**
     * Activar estudiante
     */
    public function activar()
    {
        $this->update(['activo' => true]);
    }

    /**
     * Desactivar estudiante
     */
    public function desactivar()
    {
        if ($this->puedeDesactivar()) {
            $this->update(['activo' => false]);
            return true;
        }
        return false;
    }
}
