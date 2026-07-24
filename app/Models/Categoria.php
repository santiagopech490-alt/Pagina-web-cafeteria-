<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = "categorias";
    protected $primaryKey = "CategoriaId";
    public $timestamps = false;

    protected $fillable = ["Nombre", "Icono", "Orden", "Activa"];

    public function productos()
    {
        return $this->hasMany(Producto::class, "CategoriaId", "CategoriaId");
    }
}
