<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    public function Productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'nombre',
        'abreviatura',
    ];
}
