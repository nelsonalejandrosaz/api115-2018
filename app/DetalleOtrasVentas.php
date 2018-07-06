<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleOtrasVentas extends Model
{
    public function Venta()
    {
        return $this->belongsTo('App\Venta');
    }

    protected $fillable = [
        'id',
        'venta_id',
        'detalle',
        'cantidad',
        'precio_unitario',
        'venta_exenta',
        'venta_gravada',
        'venta_total',
    ];

    protected $table = 'detalle_otras_ventas';
}
