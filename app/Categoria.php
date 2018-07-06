<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];
}
