<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Salida
 *
 * @property int $id
 * @property int|null $orden_pedido_id
 * @property int|null $produccion_id
 * @property int $unidad_medida_id
 * @property float $cantidad
 * @property float $precio_unitario
 * @property float $venta_exenta
 * @property float $venta_gravada
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\OrdenPedido|null $orden_pedido
 * @property-read \App\Produccion|null $produccion
 * @property-read \App\UnidadMedida $unidad_medida
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereProduccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaExenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Salida whereVentaGravada($value)
 * @mixin \Eloquent
 */
class Salida extends Model
{
    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function orden_pedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function produccion()
    {
        return $this->belongsTo('App\Produccion');
    }

    public function unidad_medida()
    {
        return $this->belongsTo('App\UnidadMedida');
    }

    public function precio()
    {
        return $this->belongsTo('App\Precio');
    }

    protected $fillable = [
        'orden_pedido_id',
        'produccion_id',
        'unidad_medida_id',
        'precio_id',
        'cantidad',
        'precio_unitario',
        'venta_exenta',
        'venta_gravada',
    ];
}
