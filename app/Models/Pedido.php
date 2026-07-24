<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "pedidos";
    protected $primaryKey = "PedidoId";
    public $timestamps = false;

    protected $fillable = [
        "Folio",
        "EstadoId",
        "Total",
        "MetodoEntregaId",
        "Direccion",
        "NumeroMesa",
        "Notas",
        "CuponId"
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, "PedidoId", "PedidoId");
    }
}
