<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = "facturas";
    protected $primaryKey = "FacturaId";
    public $timestamps = false;

    protected $fillable = ["PedidoId", "Folio", "Total"];

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, "FacturaId", "FacturaId");
    }
}
