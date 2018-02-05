<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCompra extends Model
{
    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'estados_compra';
}
