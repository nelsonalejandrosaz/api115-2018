<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto')->withTrashed();
    }

    public function tipo_movimiento()
    {
        return $this->belongsTo('App\TipoMovimiento');
    }

    public function entrada()
    {
        return $this->belongsTo('App\Entrada');
    }

    public function salida()
    {
        return $this->belongsTo('App\Salida');
    }

    public function ajuste()
    {
        return $this->belongsTo('App\Ajuste');
    }

    protected $fillable = [
        'producto_id',
        'tipo_movimiento_id',
        'entrada_id',
        'salida_id',
        'ajuste_id',
        'fecha',
        'detalle',
        'cantidad',
        'costo_unitario',
        'costo_total',
        'cantidad_existencia',
        'costo_unitario_existencia',
        'costo_total_existencia',
        'fecha_procesado',
        'procesado',
    ];

    protected $dates = [
        'fecha',
        'fecha_procesado',
    ];
}
