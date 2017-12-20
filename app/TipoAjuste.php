<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TipoAjuste
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ajuste[] $ajustes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoAjuste whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoAjuste extends Model
{
    public function ajustes()
    {
        return $this->hasMany('App\Ajuste');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
