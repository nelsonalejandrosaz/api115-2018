<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{

    public function orden_pedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('App\TipoDocumento');
    }

    public function estado_venta()
    {
        return $this->belongsTo('App\EstadoVenta');
    }

    public function detalle_servicios()
    {
        return $this->hasMany('App\DetalleServicio');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function abonos()
    {
        return $this->hasMany('App\Abono');
    }

    protected $fillable = [
        'tipo_documento_id',
        'numero',
        'orden_pedido_id',
        'cliente_id',
        'vendedor_id',
        'fecha',
        'ruta_archivo',
        'estado_venta_id',
        'saldo',
        'venta_total',
        'venta_total_con_impuestos',
    ];

    protected $dates = [
        'fecha',
    ];
}
