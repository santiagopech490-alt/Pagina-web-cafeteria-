<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = "mesas";
    protected $primaryKey = "MesaId";
    public $timestamps = false;

    protected $fillable = ["NumeroMesa", "Estado"];
}
