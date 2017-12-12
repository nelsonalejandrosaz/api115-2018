<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Rol
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $rol
 * @mixin \Eloquent
 */
class Rol extends Model
{
    public function rol()
    {
        return $this->hasMany('App\User');
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
