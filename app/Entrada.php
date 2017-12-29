<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entrada
 *
 * @property int $id
 * @property int $compra_id
 * @property int $movimiento_id
 * @property float $cantidad
 * @property float $costoUnitario
 * @property float $costoTotal
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Compra $compra
 * @property-read \App\Movimiento $movimiento
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entrada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Entrada extends Model
{
    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }

    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento');
    }

    protected $fillable = [
        'compra_id',
        'movimiento_id',
        'cantidad',
        'costoUnitario',
        'costoTotal',
    ];
}
