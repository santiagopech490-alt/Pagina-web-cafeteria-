<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = "detallespedido";
    protected $primaryKey = "DetalleId";
    public $timestamps = false;

    protected $fillable = ["PedidoId", "ProductoId", "Cantidad"];

    public function producto()
    {
        return $this->belongsTo(Producto::class, "ProductoId", "ProductoId");
    }
}
