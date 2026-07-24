<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoEntrega extends Model
{
    protected $table = "metodosentrega";
    protected $primaryKey = "MetodoId";
    public $timestamps = false;

    protected $fillable = ["Clave", "Etiqueta"];
}
