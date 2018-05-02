<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

    protected $fillable = [
        'nombre',
        'telefono_1',
        'telefono_2',
        'direccion',
        'nit',
        'nrc',
        'nombre_contacto',
        'saldo',
        'nacional',
        'percepcion',
    ];

    protected $table = 'proveedores';
}
