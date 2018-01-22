<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Movimiento
 *
 * @property int $id
 * @property int $producto_id
 * @property int $tipo_movimiento_id
 * @property int|null $entrada_id
 * @property int|null $salida_id
 * @property int|null $ajuste_id
 * @property \Carbon\Carbon $fecha
 * @property string|null $detalle
 * @property float|null $cantidad_existencia
 * @property float|null $costo_unitario_existencia
 * @property \Carbon\Carbon|null $fecha_procesado
 * @property int $procesado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Ajuste|null $ajuste
 * @property-read \App\Entrada|null $entrada
 * @property-read \App\Producto $producto
 * @property-read \App\Salida|null $salida
 * @property-read \App\TipoMovimiento $tipo_movimiento
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCostoUnitarioExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereEntradaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFechaProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereSalidaId($value)
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
        'cantidad_existencia',
        'costo_unitario_existencia',
        'fecha_procesado',
        'procesado',
    ];

    protected $dates = [
        'fecha',
        'fecha_procesado',
    ];
}
