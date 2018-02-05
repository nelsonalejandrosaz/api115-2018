<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    public function componentes()
    {
        return $this->hasMany('App\Componente');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function ingresado()
    {
        return $this->belongsTo('App\User','ingresado_id');
    }

    protected $fillable = [
        'producto_id',
        'ingresado_id',
        'descripcion',
        'fecha',
        'version',
        'activa',
    ];

    protected $dates = [
        'fecha',
    ];
}
