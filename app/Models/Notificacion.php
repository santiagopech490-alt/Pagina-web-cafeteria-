<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = "notificaciones";
    protected $primaryKey = "NotificacionId";
    public $timestamps = false;

    protected $fillable = [
        "UsuarioId",
        "Tipo",
        "Titulo",
        "Cuerpo",
        "Leida",
        "URL",
        "CreadoEn",
        "LeidaEn"
    ];
}
