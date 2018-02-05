<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    public function users()
    {
        return $this->hasMany('App\User');
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $table = 'roles';
}
