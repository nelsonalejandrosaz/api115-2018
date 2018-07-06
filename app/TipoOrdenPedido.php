<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoOrdenPedido extends Model
{
    public function orden_pedido()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
