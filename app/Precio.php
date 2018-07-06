<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function unidad_medida()
    {
        return$this->belongsTo('App\UnidadMedida');
    }

    protected $fillable = [
        'producto_id',
        'presentacion',
        'unidad_medida_id',
        'precio',
        'nombre_factura',
        'factor',
    ];
}
