<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResenaProducto extends Model
{
    protected $table = "resenasproductos";
    protected $primaryKey = "ResenaId";
    public $timestamps = false;

    protected $fillable = [
        "ProductoId",
        "UsuarioId",
        "PedidoId",
        "Calificacion",
        "Comentario",
        "Aprobada",
        "CreadoEn"
    ];
}
