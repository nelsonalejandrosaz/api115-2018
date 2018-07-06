<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }

    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    protected $fillable = [
        'compra_id',
        'produccion_id',
        'unidad_medida_id',
        'cantidad',
        'costo_unitario',
        'costo_total',
    ];
}
