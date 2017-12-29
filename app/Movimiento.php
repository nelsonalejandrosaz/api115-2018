<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Movimiento
 *
 * @property int $id
 * @property int $producto_id
 * @property int $tipo_movimiento_id
 * @property string $fecha
 * @property string|null $detalle
 * @property float|null $cantidadExistencia
 * @property float|null $costoUnitarioExistencia
 * @property float|null $costoTotalExistencia
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Ajuste $ajuste
 * @property-read \App\Entrada $entrada
 * @property-read \App\Producto $producto
 * @property-read \App\Salida $salida
 * @property-read \App\TipoMovimiento $tipoMovimiento
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoTotalExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoUnitarioExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereTipoMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Movimiento extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo('App\TipoMovimiento');
    }

    public function entrada()
    {
        return $this->hasOne('App\Entrada');
    }

    public function salida()
    {
        return $this->hasOne('App\Salida');
    }

    public function ajuste()
    {
        return $this->hasOne('App\Ajuste');
    }

    protected $fillable = [
        'producto_id',
        'tipo_movimiento_id',
        'fecha',
        'detalle',
        'cantidadExistencia',
        'costoUnitarioExistencia',
        'costoTotalExistencia',
    ];
}
