<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{

    public function ordenPedido()
    {
        return $this->hasOne('App\OrdenPedido');
    }

    public function ingresadoPor()
    {
        return $this->hasOne('App\User','id','ingresadoPor_id');
    }

    protected $fillable = [
        'cliente_id',
        'municipio_id',
        'numero',
        'detalle',
        'fechaIngreso',
        'ingresadoPor_id',
        'direccion',
        'monto',
        'rutaArchivo',
    ];
}
