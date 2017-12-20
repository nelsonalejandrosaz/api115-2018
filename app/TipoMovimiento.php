<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TipoMovimiento
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Movimiento[] $movimientos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoMovimiento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoMovimiento extends Model
{
    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
