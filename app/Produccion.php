<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Produccion
 *
 * @property int $id
 * @property int $bodega_id
 * @property int $formula_id
 * @property float $cantidad
 * @property string $fecha
 * @property string|null $detalle
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $bodeguero
 * @property-read \App\Formula $formula
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Salida[] $salidas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereBodegaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFormulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Produccion extends Model
{
    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodega_id');
    }

    public function formula()
    {
        return $this->belongsTo('App\Formula');
    }

    protected $fillable = [
        'bodega_id',
        'formula_id',
        'cantidad',
        'fecha',
        'detalle',
    ];

    protected $table = 'producciones';

}
