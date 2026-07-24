<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = "cupones";
    protected $primaryKey = "CuponId";
    public $timestamps = false;

    protected $fillable = [
        "Codigo",
        "Descripcion",
        "TipoDescuento",
        "ValorDescuento",
        "UsosMaximos",
        "UsosActuales",
        "ValidoDesde",
        "ValidoHasta",
        "Activo"
    ];
}
