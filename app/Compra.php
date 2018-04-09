<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
    }

    public function ingresado()
    {
        return $this->belongsTo('App\User','ingresado_id');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function estado_compra()
    {
        return $this->belongsTo('App\EstadoCompra');
    }

    protected $fillable = [
        'proveedor_id',
        'numero',
        'detalle',
        'fecha',
        'compra_total',
        'ruta_archivo',
        'ingresado_id',
        'bodega_id',
        'condicion_pago_id',
        'estado_compra_id',
        'saldo',
    ];

    protected $dates = [
        'fecha'
    ];
}
