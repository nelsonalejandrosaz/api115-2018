<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    public function conversiones()
    {
        return $this->hasMany('App\ConversionUnidadMedida','unidad_medida_origen_id');
    }

    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
    ];
}
