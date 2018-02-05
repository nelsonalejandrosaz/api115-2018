<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionPago extends Model
{
    public function ordenes_pedido()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'condiciones_pago';
}
