<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaccionPuntos extends Model
{
    protected $table = "transaccionespuntos";
    protected $primaryKey = "TransaccionId";
    public $timestamps = false;

    protected $fillable = ["UsuarioId", "TipoMovimiento", "Puntos", "FechaMovimiento"];
}
