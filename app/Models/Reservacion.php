<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = "reservaciones";
    protected $primaryKey = "ReservacionId";
    public $timestamps = false;

    protected $fillable = ["MesaId", "NombreCliente", "FechaHora"];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, "MesaId", "MesaId");
    }
}
