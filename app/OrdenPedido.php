<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPedido extends Model
{
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function venta()
    {
        return $this->hasOne('App\Venta');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function condicion_pago()
    {
        return $this->belongsTo('App\CondicionPago');
    }

    public function estado_orden()
    {
        return $this->belongsTo('App\EstadoOrdenPedido','estado_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('App\TipoDocumento');
    }

    protected $fillable = [
        'cliente_id',
        'numero',
        'detalle',
        'fecha',
        'fecha_entrega',
        'fecha_procesado',
        'condicion_pago_id',
        'vendedor_id',
        'bodega_id',
        'ventas_exentas',
        'ventas_gravadas',
        'venta_total',
        'ruta_archivo',
        'estado_id',
        'tipo_documento_id',
        'tipo_orden_pedido_id',
    ];

    protected $dates = [
        'fecha',
        'fecha_entrega',
        'fecha_procesado',
    ];
}
