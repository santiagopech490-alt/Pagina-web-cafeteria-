<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAuditoria extends Model
{
    protected $table = "logsauditoria";
    protected $primaryKey = "LogId";
    public $timestamps = false;

    protected $fillable = [
        "UsuarioId",
        "Accion",
        "Tabla",
        "RegistroId",
        "ValorAntes",
        "ValorDespues",
        "IPOrigen",
        "CreadoEn"
    ];
}
