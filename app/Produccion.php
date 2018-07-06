<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produccion extends Model
{
    use SoftDeletes;

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

    public function producto()
    {
        return $this->belongsTo('App\Producto')->withTrashed();
    }

    public function entrada()
    {
        return $this->hasOne('App\Entrada');
    }

    protected $fillable = [
        'bodega_id',
        'formula_id',
        'producto_id',
        'cantidad',
        'fecha',
        'detalle',
        'lote',
        'fecha_vencimiento',
        'procesado',
    ];

    protected $dates = [
        'fecha'
    ];

    protected $table = 'producciones';

}
