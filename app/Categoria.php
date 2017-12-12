<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Categoria
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoria whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Categoria extends Model
{
    public function Productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];
}
