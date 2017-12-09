<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function Productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];
}
