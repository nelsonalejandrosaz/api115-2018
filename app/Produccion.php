<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Produccion
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $bodeguero_id
 * @property int $formula_id
 * @property float $cantidad
 * @property string $fecha
 * @property string|null $detalle
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereBodegueroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereFormulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Produccion whereUpdatedAt($value)
 */
class Produccion extends Model
{
    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function bodeguero()
    {
        return $this->belongsTo('App\User','bodeguero_id');
    }

    public function formula()
    {
        return $this->belongsTo('App\Formula');
    }

    protected $fillable = [
        'bodeguero_id',
        'formula_id',
        'cantidad',
        'fecha',
        'detalle',
    ];

    protected $table = 'producciones';

}
