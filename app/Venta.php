<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Venta
 *
 * @property int $id
 * @property int $tipo_documento_id
 * @property string|null $numero
 * @property string $fechaIngreso
 * @property int $vendedor_id
 * @property string|null $nit
 * @property string $rutaArchivo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\OrdenPedido $ordenPedido
 * @property-read \App\User $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereTipoDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereVendedorId($value)
 * @mixin \Eloquent
 * @property-read \App\TipoDocumento $tipoDocumento
 * @property string $procesado
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereProcesado($value)
 * @property string|null $nrc
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereNrc($value)
 */
class Venta extends Model
{

    public function ordenPedido()
    {
        return $this->hasOne('App\OrdenPedido');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','id','vendedor_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo('App\TipoDocumento');
    }

    protected $fillable = [
        'tipo_documento_id',
        'numero',
        'fechaIngreso',
        'vendedor_id',
        'nit',
        'nrc',
        'rutaArchivo',
        'procesado',
    ];

    protected $dates = [
        'fechaIngreso',
    ];
}
