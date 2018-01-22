<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EstadoVenta
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Venta[] $ventas
 * @mixin \Eloquent
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoVenta whereUpdatedAt($value)
 */
class EstadoVenta extends Model
{
    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'estados_ventas';
}
