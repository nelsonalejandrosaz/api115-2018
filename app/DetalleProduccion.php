<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function bodega()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    protected $fillable = [
        'bodega_id',
        'produccion_id',
    ];

    protected $table = 'detalle_producciones';
}
