<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TipoProducto
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoProducto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoProducto extends Model
{
    public function Productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
