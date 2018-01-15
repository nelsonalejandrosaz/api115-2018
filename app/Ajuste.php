<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ajuste
 *
 * @property int $id
 * @property int $movimiento_id
 * @property int $tipo_ajuste_id
 * @property string $detalle
 * @property string $fechaIngreso
 * @property int $realizado_id
 * @property float $cantidadAnterior
 * @property float $valorUnitarioAnterior
 * @property float $cantidadAjuste
 * @property float $valorUnitarioAjuste
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\User $realizado
 * @property-read \App\TipoAjuste $tipoAjuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereFechaIngreso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereRealizadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereTipoAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAnterior($value)
 * @mixin \Eloquent
 * @property float $diferenciaCantidadAjuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDiferenciaCantidadAjuste($value)
 */
class Ajuste extends Model
{
    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento');
    }

    public function tipoAjuste()
    {
        return $this->belongsTo('App\TipoAjuste','tipo_ajuste_id');
    }

    public function realizado()
    {
        return $this->belongsTo('App\User','realizado_id');
    }

    protected $fillable = [
        'movimiento_id',
        'tipo_ajuste_id',
        'detalle',
        'fechaIngreso',
        'realizado_id',
        'cantidadAnterior',
        'valorUnitarioAnterior',
        'cantidadAjuste',
        'valorUnitarioAjuste',
        'diferenciaCantidadAjuste',
    ];
}
