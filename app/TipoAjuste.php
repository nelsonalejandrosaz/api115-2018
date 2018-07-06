<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAjuste extends Model
{
    public function ajustes()
    {
        return $this->hasMany('App\Ajuste');
    }

    protected $fillable = [
        'codigo',
        'tipo',
        'nombre',
    ];
}
