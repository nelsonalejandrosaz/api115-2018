<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EstadoCompra
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Compra[] $compras
 * @mixin \Eloquent
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EstadoCompra whereUpdatedAt($value)
 */
class EstadoCompra extends Model
{
    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'estados_compra';
}
