<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function orden_pedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    public function precio()
    {
        return $this->belongsTo('App\Precio');
    }

    protected $fillable = [
        'orden_pedido_id',
        'produccion_id',
        'unidad_medida_id',
        'precio_id',
        'descripcion_presentacion',
        'cantidad',
        'precio_unitario',
        'venta_exenta',
        'venta_gravada',
    ];
}
