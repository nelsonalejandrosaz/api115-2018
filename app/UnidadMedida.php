<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UnidadMedida
 *
 * @property int $id
 * @property string $nombre
 * @property string $abreviatura
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereAbreviatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnidadMedida extends Model
{
    public function Productos()
    {
        $this->hasMany('App\Producto');
    }

    protected $fillable = [
        'nombre',
        'abreviatura',
    ];
}
