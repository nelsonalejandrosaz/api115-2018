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
 * @property \Carbon\Carbon $fecha
 * @property int $realizado_id
 * @property float $cantidad_anterior
 * @property float $valor_unitario_anterior
 * @property float|null $cantidad_ajuste
 * @property float|null $valor_unitario_ajuste
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Movimiento $movimiento
 * @property-read \App\User $realizado
 * @property-read \App\TipoAjuste $tipo_ajuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCantidadAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDiferenciaCantidadAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereMovimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereRealizadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereTipoAjusteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAjuste($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereValorUnitarioAnterior($value)
 * @mixin \Eloquent
 * @property float $diferencia_ajuste
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ajuste whereDiferenciaAjuste($value)
 */
class Ajuste extends Model
{
    public function movimiento()
    {
        return $this->belongsTo('App\Movimiento');
    }

    public function tipo_ajuste()
    {
        return $this->belongsTo('App\TipoAjuste','tipo_ajuste_id');
    }

    public function realizado()
    {
        return $this->belongsTo('App\User','realizado_id');
    }

    protected $fillable = [
        'tipo_ajuste_id',
        'detalle',
        'fecha',
        'realizado_id',
        'cantidad_anterior',
        'valor_unitario_anterior',
        'cantidad_ajuste',
        'valor_unitario_ajuste',
        'diferencia_ajuste',
    ];

    protected $dates = [
        'fecha',
    ];
}
