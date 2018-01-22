<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EstadoOrdenPedido
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedido
 * @mixin \Eloquent
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoOrdenPedido whereUpdatedAt($value)
 */
class EstadoOrdenPedido extends Model
{
    public function ordenes_pedido()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'estados_orden_pedido';
}
