<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Rol
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $rol
 * @mixin \Eloquent
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rol whereUpdatedAt($value)
 */
class Rol extends Model
{
    public function rol()
    {
        return $this->hasMany('App\User');
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $table = 'roles';
}
