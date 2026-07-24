<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSistema extends Model
{
    protected $table = "configuracionsistema";
    protected $primaryKey = "ConfigId";
    public $timestamps = false;

    protected $fillable = ["Clave", "Valor", "Descripcion", "Editable", "ModificadoEn"];
}
