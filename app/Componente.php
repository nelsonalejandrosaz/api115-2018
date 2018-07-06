<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    public function formula()
    {
        return $this->belongsTo('App\Formula');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    protected $fillable = [
        'formula_id', 'producto_id', 'porcentaje', 'cantidad',
    ];
}
