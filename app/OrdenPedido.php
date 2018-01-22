<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrdenPedido
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $numero
 * @property string|null $detalle
 * @property \Carbon\Carbon $fecha
 * @property \Carbon\Carbon|null $fecha_procesado
 * @property \Carbon\Carbon|null $fecha_entrega
 * @property string|null $condicion_pago_id
 * @property int $vendedor_id
 * @property int|null $bodega_id
 * @property float|null $ventas_exentas
 * @property float|null $ventas_gravadas
 * @property float|null $venta_total
 * @property string $ruta_archivo
 * @property int $estado_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $bodeguero
 * @property-read \App\Cliente $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Salida[] $salidas
 * @property-read \App\User $vendedor
 * @property-read \App\Venta $venta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCondicionPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasExentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasGravadas($value)
 * @mixin \Eloquent
 * @property-read \App\CondicionPago|null $condicion_pago
 */
class OrdenPedido extends Model
{
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function venta()
    {
        return $this->hasOne('App\Venta');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function condicion_pago()
    {
        return $this->belongsTo('App\CondicionPago');
    }

    protected $fillable = [
        'cliente_id',
        'numero',
        'detalle',
        'fecha',
        'fecha_entrega',
        'fecha_procesado',
        'condicion_pago_id',
        'vendedor_id',
        'bodega_id',
        'ventas_exentas',
        'ventas_gravadas',
        'venta_total',
        'ruta_archivo',
        'estado_id',
    ];

    protected $dates = [
        'fecha',
        'fecha_entrega',
        'fecha_procesado',
    ];
}
