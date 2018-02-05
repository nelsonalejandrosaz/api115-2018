<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function formula()
    {
        return $this->belongsTo('App\Formula');
    }

    public function detalle_producciones()
    {
        return $this->hasMany('App\DetalleProduccion');
    }

    protected $fillable = [
        'bodega_id',
        'formula_id',
        'cantidad',
        'fecha',
        'detalle',
        'lote',
        'fecha_vencimiento',
    ];

    protected $table = 'producciones';

}
