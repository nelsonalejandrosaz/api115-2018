<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entrada
 *
 * @property int $id
 * @property int|null $compra_id
 * @property int|null $produccion_id
 * @property int $unidad_medida_id
 * @property float $cantidad
 * @property float $costo_unitario
 * @property float $costo_total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Compra|null $compra
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\Produccion|null $produccion
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereProduccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Entrada extends Model
{
    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }

    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    protected $fillable = [
        'compra_id',
        'produccion_id',
        'unidad_medida_id',
        'cantidad',
        'costo_unitario',
        'costo_total',
    ];
}
