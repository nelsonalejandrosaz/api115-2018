<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Producto
 *
 * @property int $id
 * @property int $unidad_medida_id
 * @property int $tipo_producto_id
 * @property int $categoria_id
 * @property string $nombre
 * @property string|null $codigo
 * @property float $existenciaMin
 * @property float $existenciaMax
 * @property float $cantidadExistencia
 * @property float $costo
 * @property float $precio
 * @property float $margenGanancia
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Categoria $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Movimiento[] $movimientos
 * @property-read \App\TipoProducto $tipoProducto
 * @property-read \App\UnidadMedida $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCosto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereExistenciaMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereExistenciaMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereMargenGanancia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereTipoProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Producto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    protected $fillable = [
        'unidad_medida_id',
        'tipo_producto_id',
        'categoria_id',
        'nombre',
        'codigo',
        'existenciaMin',
        'existenciaMax',
        'cantidadExistencia',
        'costo',
        'precio',
        'margenGanancia',
    ];
}
