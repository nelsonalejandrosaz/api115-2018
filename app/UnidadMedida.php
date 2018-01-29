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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConversionUnidadMedida[] $conversiones
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereAbreviatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UnidadMedida whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnidadMedida extends Model
{
    public function conversiones()
    {
        return $this->hasMany('App\ConversionUnidadMedida','unidad_medida_origen_id');
    }

    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
    ];
}
