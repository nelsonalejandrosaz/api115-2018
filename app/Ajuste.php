<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function tipo_ajuste()
    {
        return $this->belongsTo('App\TipoAjuste','tipo_ajuste_id');
    }

    public function realizado()
    {
        return $this->belongsTo('App\User','realizado_id');
    }

    protected $fillable = [
        'tipo_ajuste_id',
        'detalle',
        'fecha',
        'realizado_id',
        'cantidad_anterior',
        'valor_unitario_anterior',
        'cantidad_ajuste',
        'valor_unitario_ajuste',
        'diferencia_ajuste',
    ];

    protected $dates = [
        'fecha',
    ];
}
