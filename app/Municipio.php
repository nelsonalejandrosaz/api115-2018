<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Municipio
 *
 * @property int $id
 * @property string $nombre
 * @property int $departamento_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenesPedidos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Municipio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Municipio extends Model
{
    public function ordenesPedidos()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'nombre', 'departamento_id',
    ];
}
