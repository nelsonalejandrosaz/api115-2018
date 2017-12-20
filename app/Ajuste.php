<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ajuste
 *
 * @property int $id
 * @property int $tipo_ajuste_id
 * @property string $detalle
 * @property string $fechaIngreso
 * @property int $realizadoPor_id
 * @property float $cantidadAnterior
 * @property float $valorUnitarioAnterior
 * @property float $cantidadAjuste
 * @property float $valorUnitarioAjuste
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\TipoAjuste $tipoAjuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereRealizadoPorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereTipoAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAnterior($value)
 * @mixin \Eloquent
 */
class Ajuste extends Model
{
    public function tipoAjuste()
    {
        return $this->hasOne('App\TipoAjuste','id','tipo_ajuste_id');
    }

    public function movimiento()
    {
        return $this->hasOne('App\Movimiento');
    }

    public function realizadoPor()
    {
        return $this->hasOne('App\User','id','realizadoPor_id');
    }

    protected $fillable = [
        'tipo_ajuste_id',
        'detalle',
        'fechaIngreso',
        'realizadoPor_id',
        'cantidadAnterior',
        'valorUnitarioAnterior',
        'cantidadAjuste',
        'valorUnitarioAjuste',
    ];
}
