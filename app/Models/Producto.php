<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "productos";
    protected $primaryKey = "ProductoId";
    public $timestamps = false;

    protected $fillable = [
        "Codigo",
        "Nombre",
        "Precio",
        "Existencia",
        "CategoriaId",
        "Disponible",
        "Destacado"
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, "CategoriaId", "CategoriaId");
    }
}
