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
 * @property string $fechaIngreso
 * @property float|null $compraTotal
 * @property int|null $ingresado_id
 * @property int|null $bodeguero_id
 * @property string $rutaArchivo
 * @property int $revisado
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User|null $bodeguero
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entrada[] $entradas
 * @property-read \App\User|null $ingresado
 * @property-read \App\Proveedor $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereBodegueroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCompraTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereIngresadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRevisado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Compra whereRutaArchivo($value)
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
        return $this->belongsTo('App\User','bodeguero_id');
    }

    protected $fillable = [
        'proveedor_id',
        'numero',
        'detalle',
        'fechaIngreso',
        'compraTotal',
        'rutaArchivo',
        'ingresado_id',
        'bodeguero_id',
        'revisado',
    ];
}
