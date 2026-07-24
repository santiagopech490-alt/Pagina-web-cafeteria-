<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleados";
    protected $primaryKey = "EmpleadoId";
    public $timestamps = false;

    protected $fillable = [
        "UsuarioId",
        "Nombre",
        "ApellidoP",
        "ApellidoM",
        "CURP",
        "Puesto",
        "SalarioDia",
        "Telefono",
        "FechaIngreso",
        "Activo"
    ];
}
