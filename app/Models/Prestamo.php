<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Prestamo extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'libro_id',
        'estudiante_id',
        'nombre_estudiante',
        'carnet_estudiante',
        'fecha_prestamo',
        'fecha_limite',
        'fecha_devolucion',
        'dias_retraso',
        'tiene_retraso',
        'estado',
    ];

    /**
     * Los atributos que deben ser convertidos a fechas.
     */
    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_limite' => 'date',
        'fecha_devolucion' => 'datetime',
        'tiene_retraso' => 'boolean',
    ];

    /**
     * Relación: Un préstamo pertenece a un libro
     */
    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    /**
     * Relación: Un préstamo pertenece a un estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    /**
     * Verificar si el préstamo está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Calcular días de retraso al momento de devolver
     */
    public function calcularRetraso()
    {
        if (!$this->fecha_devolucion || !$this->fecha_limite) {
            return 0;
        }

        $fechaDevolucion = Carbon::parse($this->fecha_devolucion);
        $fechaLimite = Carbon::parse($this->fecha_limite);

        if ($fechaDevolucion->greaterThan($fechaLimite)) {
            return $fechaDevolucion->diffInDays($fechaLimite);
        }

        return 0;
    }

    /**
     * Verificar si el préstamo tiene retraso
     */
    public function tieneRetraso()
    {
        return $this->calcularRetraso() > 0;
    }

    /**
     * Obtener el estado del préstamo con texto legible
     */
    public function getEstadoTexto()
    {
        if ($this->estado === 'devuelto') {
            return $this->tiene_retraso ? 'Devuelto con Retraso' : 'Devuelto a Tiempo';
        }
        
        if ($this->estado === 'activo') {
            $hoy = Carbon::now();
            $fechaLimite = Carbon::parse($this->fecha_limite);
            
            if ($hoy->greaterThan($fechaLimite)) {
                return 'Vencido';
            }
            
            return 'Activo';
        }

        return ucfirst($this->estado);
    }

    /**
     * Obtener color del badge según el estado
     */
    public function getColorBadge()
    {
        if ($this->estado === 'devuelto') {
            return $this->tiene_retraso ? 'danger' : 'success';
        }

        $hoy = Carbon::now();
        $fechaLimite = Carbon::parse($this->fecha_limite);

        if ($hoy->greaterThan($fechaLimite)) {
            return 'danger';
        }

        return 'warning';
    }

    /**
     * Obtener nombre del estudiante (desde relación o campo legacy)
     */
    public function getNombreEstudiante()
    {
        return $this->estudiante ? $this->estudiante->nombre : $this->nombre_estudiante;
    }

    /**
     * Obtener carnet del estudiante (desde relación o campo legacy)
     */
    public function getCarnetEstudiante()
    {
        return $this->estudiante ? $this->estudiante->carnet : $this->carnet_estudiante;
    }
}
