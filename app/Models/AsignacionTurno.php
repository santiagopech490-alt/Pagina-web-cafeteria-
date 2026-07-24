<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionTurno extends Model
{
    protected $table = "asignacionturnos";
    protected $primaryKey = "AsignacionId";
    public $timestamps = false;

    protected $fillable = ["EmpleadoId", "TurnoId", "Fecha", "CheckIn", "CheckOut"];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, "EmpleadoId", "EmpleadoId");
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, "TurnoId", "TurnoId");
    }
}
