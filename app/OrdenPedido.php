<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPedido extends Model
{
    public function cliente()
    {
        return $this->hasOne('App\Cliente');
    }

    public function municipio()
    {
        return $this->hasOne('App\Municipio');
    }

    public function factura()
    {
        return $this->hasOne('App\Factura');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    public function revisadoPor()
    {
        return $this->hasOne('App\User','id','revisadoPor_id');
    }

    protected $fillable = [
        'factura_id',
        'produccion_id',
        'cliente_id',
        'municipio_id',
        'direccion',
        'numero',
        'detalle',
        'fechaIngreso',
        'fechaEntrega',
        'ingresadoPor_id',
        'revisadoPor_id',
        'rutaArchivo',
        'revisado',
    ];
}
