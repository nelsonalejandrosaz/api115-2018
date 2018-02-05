<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversionUnidadMedida extends Model
{
    public function unidad_origen()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_medida_origen_id');
    }

    public function unidad_destino()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_medida_destino_id');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida_origen_id',
        'unidad_medida_destino_id',
        'factor',
    ];

    protected $table = 'conversion_unidades_medidas';

}
