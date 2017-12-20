<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Movimiento
 *
 * @property int $id
 * @property int|null $producto_id
 * @property int|null $orden_pedido_id
 * @property int|null $venta_id
 * @property int|null $ajuste_id
 * @property int|null $tipo_movimiento_id
 * @property string $fecha
 * @property string $detalle
 * @property float $cantidadMovimiento
 * @property float $valorUnitarioMovimiento
 * @property float $valorTotalMovimiento
 * @property float|null $cantidadExistencia
 * @property float|null $valorUnitarioExistencia
 * @property float|null $valorTotalExistencia
 * @property int $procesado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Compra $compra
 * @property-read \App\Producto|null $producto
 * @property-read \App\TipoMovimiento|null $tipoMovimiento
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidadExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCantidadMovimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereTipoMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereValorTotalExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereValorTotalMovimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereValorUnitarioExistencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereValorUnitarioMovimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereVentaId($value)
 * @mixin \Eloquent
 * @property int|null $compra_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Movimiento whereCompraId($value)
 */
class Movimiento extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function ordenPedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }

    public function ajuste()
    {
        return $this->belongsTo('App\Ajuste');
    }

    public function tipoMovimiento()
    {
        return $this->belongsTo('App\TipoMovimiento');
    }

    protected $fillable = [
        'producto_id',
        'orden_pedido_id',
        'compra_id',
        'ajuste_id',
        'tipo_movimiento_id',
        'fecha',
        'detalle',
        'cantidadMovimiento',
        'valorUnitarioMovimiento',
        'valorTotalMovimiento',
        'cantidadExistencia',
        'valorUnitarioExistencia',
        'valorTotalExistencia',
        'procesado',
    ];
}
