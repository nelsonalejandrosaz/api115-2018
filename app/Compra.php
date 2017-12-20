<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Compra
 *
 * @property int $id
 * @property int $proveedor_id
 * @property int $numero
 * @property string $detalle
 * @property string $fechaIngreso
 * @property float $monto
 * @property int $ingresadoPor
 * @property int $revisadoPor
 * @property string $rutaArchivo
 * @property int $ingresadoInventario
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Movimiento[] $movimientos
 * @property-read \App\Proveedor $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoInventario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRevisadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $ingresadoPor_id
 * @property int|null $revisadoPor_id
 * @property int|null $revisado
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoPorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRevisado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRevisadoPorId($value)
 */
class Compra extends Model
{
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    public function ingresadoPor()
    {
        return $this->hasOne('App\User','id','ingresadoPor_id');
    }

    protected $fillable = [
        'proveedor_id',
        'numero',
        'detalle',
        'fechaIngreso',
        'monto',
        'rutaArchivo',
        'ingresadoPor_id',
        'revisadoPor_id',
        'revisado',
    ];
}
