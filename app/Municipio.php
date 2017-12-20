<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public function ordenesPedidos()
    {
        return $this->hasMany('App\Factura');
    }

    protected $fillable = [
        'nombre', 'departamento_id',
    ];
}
