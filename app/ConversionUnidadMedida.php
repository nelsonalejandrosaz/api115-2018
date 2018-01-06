<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ConversionUnidadMedida
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $unidadMedidaOrigen_id
 * @property int $unidadMedidaDestino_id
 * @property float $factor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\UnidadMedida $unidadDestino
 * @property-read \App\UnidadMedida $unidadOrigen
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUnidadMedidaDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUnidadMedidaOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConversionUnidadMedida whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConversionUnidadMedida extends Model
{
    public function unidadOrigen()
    {
        return $this->belongsTo('App\UnidadMedida','unidadMedidaOrigen_id');
    }

    public function unidadDestino()
    {
        return $this->belongsTo('App\UnidadMedida','unidadMedidaDestino_id');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'unidadMedidaOrigen_id',
        'unidadMedidaDestino_id',
        'factor',
    ];

    protected $table = 'conversion_unidades_medidas';
}
