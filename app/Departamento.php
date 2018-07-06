<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public function municipios()
    {
        return $this->hasMany('App\Municipio');
    }

    protected $fillable = [
        'nombre', 'isocode', 'zonesv_id',
    ];
}
