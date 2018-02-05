<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoOrdenPedido extends Model
{
    public function ordenes_pedido()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'estados_orden_pedido';
}
