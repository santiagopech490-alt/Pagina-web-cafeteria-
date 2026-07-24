<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = "detallesfactura";
    protected $primaryKey = "DetalleFactId";
    public $timestamps = false;

    protected $fillable = ["FacturaId", "Descripcion", "Cantidad"];
}
