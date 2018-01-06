<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Componente
 *
 * @property int $id
 * @property int $formula_id
 * @property int $producto_id
 * @property float $porcentaje
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Formula $formula
 * @property-read \App\Producto $producto
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereFormulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente wherePorcentaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Componente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Componente extends Model
{
    public function formula()
    {
        return $this->belongsTo('App\Formula');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    protected $fillable = [
        'formula_id', 'producto_id', 'porcentaje',
    ];
}
