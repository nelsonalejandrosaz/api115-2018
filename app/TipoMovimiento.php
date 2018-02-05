<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
