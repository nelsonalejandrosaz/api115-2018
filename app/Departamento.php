<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Departamento
 *
 * @property int $id
 * @property string $nombre
 * @property string $isocode
 * @property int $zonesv_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Municipio[] $municipios
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereIsocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Departamento whereZonesvId($value)
 * @mixin \Eloquent
 */
class Departamento extends Model
{
    public function municipios()
    {
        return $this->hasMany('App\Municipio');
    }

    protected $fillable = [
        'nombre', 'isocode', 'zonesv_id',
    ];
}
