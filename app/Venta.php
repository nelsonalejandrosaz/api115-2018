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

    public function detalle_otras_ventas()
    {
        return $this->hasMany('App\DetalleOtrasVentas');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function abonos()
    {
        return $this->hasMany('App\Abono');
    }

    public function condicion_pago()
    {
        return $this->belongsTo('App\CondicionPago');
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
        'suma',
        'flete',
        'fecha_anulado',
        'fecha_liquidado',
        'comision',
        'condicion_pago_id',
    ];

    protected $dates = [
        'fecha',
        'fecha_anulado',
        'fecha_liquidado',
    ];
}
