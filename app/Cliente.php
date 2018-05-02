<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function ordenes_pedidos()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Municipio');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function abonos()
    {
        return $this->hasMany('App\Abono');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    protected $fillable = [
        'municipio_id',
        'nombre',
        'nombre_alternativo',
        'telefono_1',
        'telefono_2',
        'direccion',
        'vendedor_id',
        'nit',
        'nrc',
        'giro',
        'nombre_contacto',
        'numero_registro',
        'saldo',
        'correo',
        'retencion',
    ];

}
