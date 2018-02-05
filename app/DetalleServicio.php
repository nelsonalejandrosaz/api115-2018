<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleServicio extends Model
{
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function servicio()
    {
        return $this->belongsTo('App\Servicio');
    }

    protected $fillable = [
        'servicio_id',
        'venta_id',
        'cantidad',
        'precio_unitario',
        'venta_exenta',
        'venta_gravada',
        'fecha_procesado',
        'procesado',
    ];

    protected $table = 'detalle_servicios';
}
