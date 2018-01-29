<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Venta
 *
 * @property int $id
 * @property int $tipo_documento_id
 * @property int $orden_pedido_id
 * @property int $estado_venta_id
 * @property string|null $numero
 * @property \Carbon\Carbon $fecha
 * @property int $vendedor_id
 * @property string $ruta_archivo
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\OrdenPedido $orden_pedido
 * @property-read \App\TipoDocumento $tipo_documento
 * @property-read \App\User $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereEstadoVentaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereOrdenPedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereTipoDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Venta whereVendedorId($value)
 * @mixin \Eloquent
 * @property-read \App\EstadoVenta $estado_venta
 */
class Venta extends Model
{

    public function orden_pedido()
    {
        return $this->belongsTo('App\OrdenPedido');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','id','vendedor_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('App\TipoDocumento');
    }

    public function estado_venta()
    {
        return $this->belongsTo('App\EstadoVenta');
    }

    protected $fillable = [
        'tipo_documento_id',
        'numero',
        'orden_pedido_id',
        'fecha',
        'vendedor_id',
        'ruta_archivo',
        'estado_venta_id',
        'saldo',
        'venta_total_con_impuestos',
    ];

    protected $dates = [
        'fecha',
    ];
}
