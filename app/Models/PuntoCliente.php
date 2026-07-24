<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoCliente extends Model
{
    protected $table = "puntoscliente";
    protected $primaryKey = "PuntosId";
    public $timestamps = false;

    protected $fillable = ["UsuarioId", "Puntos"];
}
