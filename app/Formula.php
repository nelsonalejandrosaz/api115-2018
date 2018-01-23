<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Formula
 *
 * @property int $id
 * @property int $producto_id
 * @property string $fecha
 * @property string $ingresado_id
 * @property string|null $descripcion
 * @property int $activa
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Componente[] $componentes
 * @property-read \App\User $ingresado
 * @property-read \App\Producto $producto
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereActiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereIngresadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Formula whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Formula extends Model
{
    public function componentes()
    {
        return $this->hasMany('App\Componente');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function ingresado()
    {
        return $this->belongsTo('App\User','ingresado_id');
    }

    protected $fillable = [
        'producto_id',
        'ingresado_id',
        'descripcion',
        'fecha',
        'version',
        'activa',
    ];

    protected $dates = [
        'fecha',
    ];
}
