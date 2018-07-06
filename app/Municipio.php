<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }

    protected $fillable = [
        'nombre', 'departamento_id',
    ];
}
