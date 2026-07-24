<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = "roles";
    protected $primaryKey = "RolId";
    public $timestamps = false;

    protected $fillable = ["Nombre", "Descripcion"];
}
