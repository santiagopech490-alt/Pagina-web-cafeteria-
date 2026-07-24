<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = "turnos";
    protected $primaryKey = "TurnoId";
    public $timestamps = false;

    protected $fillable = ["Nombre", "HoraInicio", "HoraFin"];
}
