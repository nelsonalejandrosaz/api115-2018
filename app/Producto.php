<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public function unidadMedida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    public function tipoProducto()
    {
        return $this->belongsTo('App\TipoProducto');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    protected $fillable = [
        'unidadMedida_id',
        'tipoProducto_id',
        'categoria_id',
        'nombre',
        'codigo',
        'existenciaMin',
        'existenciaMax',
        'cantidad',
        'precioCompra',
        'precioVenta',
        'margenGanancia',
    ];
}
