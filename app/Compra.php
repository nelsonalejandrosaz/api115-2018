<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Compra
 *
 * @property int $id
 * @property int $proveedor_id
 * @property int $numero
 * @property string|null $detalle
 * @property string $fecha
 * @property float|null $compra_total
 * @property int|null $ingresado_id
 * @property int|null $bodega_id
 * @property string $ruta_archivo
 * @property int $condicion_pago_id
 * @property int $estado_compra_id
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $bodeguero
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entrada[] $entradas
 * @property-read \App\EstadoCompra $estado
 * @property-read \App\User|null $ingresado
 * @property-read \App\Proveedor $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCompraTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCondicionPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereEstadoCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Compra extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
    }

    public function ingresado()
    {
        return $this->belongsTo('App\User','ingresado_id');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function estado()
    {
        return $this->belongsTo('App\EstadoCompra');
    }

    protected $fillable = [
        'proveedor_id',
        'numero',
        'detalle',
        'fecha',
        'compra_total',
        'ruta_archivo',
        'ingresado_id',
        'bodega_id',
        'condicion_pago_id',
        'estado_compra_id',
        'saldo',
    ];
}
