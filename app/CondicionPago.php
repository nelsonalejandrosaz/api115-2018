<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CondicionPago
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedido
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CondicionPago whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CondicionPago extends Model
{
    public function ordenes_pedido()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'condiciones_pago';
}
