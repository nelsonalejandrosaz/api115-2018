<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Precio
 *
 * @property int $id
 * @property int $producto_id
 * @property string $presentacion
 * @property int $unidad_medida_id
 * @property float $precio
 * @property float|null $margen_ganancia
 * @property float $factor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Producto $producto
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereMargenGanancia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio wherePresentacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Precio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Precio extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function unidad_medida()
    {
        return$this->belongsTo('App\UnidadMedida');
    }

    protected $fillable = [
        'producto_id',
        'presentacion',
        'unidad_medida_id',
        'precio',
        'nombre_factura',
        'factor',
    ];
}
