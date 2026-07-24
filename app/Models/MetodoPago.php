<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = "metodospago";
    protected $primaryKey = "MetodoPagoId";
    public $timestamps = false;

    protected $fillable = ["Clave", "Etiqueta", "Activo"];
}
