<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrdenPedido
 *
 * @property int $id
 * @property int|null $venta_id
 * @property int $cliente_id
 * @property int $municipio_id
 * @property string|null $direccion
 * @property int $numero
 * @property string|null $detalle
 * @property string $fechaIngreso
 * @property string|null $fechaEntrega
 * @property string|null $condicionPago
 * @property int $vendedor_id
 * @property int|null $bodeguero_id
 * @property float $ventasExentas
 * @property float $ventasGrabadas
 * @property float $ventaTotal
 * @property string $rutaArchivo
 * @property int $procesado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $bodeguero
 * @property-read \App\Cliente $cliente
 * @property-read \App\Municipio $municipio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Salida[] $salidas
 * @property-read \App\User $vendedor
 * @property-read \App\Venta|null $venta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereBodegueroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCondicionPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereProcesado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasExentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasGrabadas($value)
 * @mixin \Eloquent
 * @property float|null $ventasGravadas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdenPedido whereVentasGravadas($value)
 */
class OrdenPedido extends Model
{
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Municipio');
    }

    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodeguero_id');
    }

    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    protected $fillable = [
        'venta_id',
        'cliente_id',
        'municipio_id',
        'direccion',
        'numero',
        'detalle',
        'fechaIngreso',
        'fechaEntrega',
        'condicionPago',
        'vendedor_id',
        'bodeguero_id',
        'ventasExentas',
        'ventasGravadas',
        'ventaTotal',
        'rutaArchivo',
        'procesado',
    ];

    protected $dates = [
        'fechaIngreso',
        'fechaEntrega',
    ];
}