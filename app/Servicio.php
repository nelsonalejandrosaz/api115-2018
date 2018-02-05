<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    public function detalle_servicios()
    {
        return $this->hasMany('App\DetalleServicio');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'costo',
    ];
}
