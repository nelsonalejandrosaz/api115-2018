<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Salida
 *
 * @property int $id
 * @property int $movimiento_id
 * @property int|null $orden_pedido_id
 * @property int|null $produccion_id
 * @property float $cantidad
 * @property float $precioUnitario
 * @property float $ventaExenta
 * @property float $ventaGravada
 * @property float $costoUnitario
 * @property float $costoTotal
 * @property int $exento
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\OrdenPedido|null $ordenPedido
 * @property-read \App\Produccion|null $produccion
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCostoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereExento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereProduccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaExenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaGravada($value)
 * @mixin \Eloquent
 * @property int $unidad_medida_id
 * @property-read \App\UnidadMedida $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUnidadMedidaId($value)
 */
class Salida extends Model
{
    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento');
    }

    public function ordenPedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function unidadMedida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    protected $fillable = [
        'movimiento_id',
        'orden_pedido_id',
        'produccion_id',
        'cantidad',
        'unidad_medida_id',
        'precioUnitario',
        'costoTotal',
        'costoUnitario',
        'ventaExenta',
        'ventaGravada',
        'exento',
    ];
}
