<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    public function tipo_producto()
    {
        return $this->belongsTo('App\TipoProducto');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    public function formulas()
    {
        return $this->hasMany('App\Formula');
    }

    public function precios()
    {
        return $this->hasMany('App\Precio');
    }

    protected $fillable = [
        'categoria_id',
        'tipo_producto_id',
        'unidad_medida_id',
        'nombre',
        'nombre_alternativo',
        'codigo',
        'existencia_min',
        'existencia_max',
        'cantidad_existencia',
        'cantidad_reserva',
        'costo',
        'factor_volumen',
        'producto_activo',
        'formula_activa',
        'unidad_factor',
    ];
}
