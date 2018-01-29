<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Abono
 *
 * @property-read \App\Cliente $cliente
 * @property-read \App\Venta $venta
 * @mixin \Eloquent
 * @property int $id
 * @property int $venta_id
 * @property int $cliente_id
 * @property \Carbon\Carbon $fecha
 * @property string|null $detalle
 * @property float $cantidad
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Abono whereVentaId($value)
 */
class Abono extends Model
{
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    protected $fillable = [
        'venta_id',
        'cliente_id',
        'fecha',
        'detalle',
        'cantidad',
        'forma_pago_id',
    ];

    protected $dates = [
        'fecha',
    ];
}
