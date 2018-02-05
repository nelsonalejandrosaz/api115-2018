<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    protected $fillable = [
        'bodega_id',
        'produccion_id',
    ];
}
