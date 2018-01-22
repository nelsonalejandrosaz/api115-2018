<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ConversionUnidadMedida
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $unidad_medida_origen_id
 * @property int $unidad_medida_destino_id
 * @property float $factor
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\UnidadMedida $unidad_destino
 * @property-read \App\UnidadMedida $unidad_origen
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
    public function unidad_origen()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_medida_origen_id');
    }

    public function unidad_destino()
    {
        return $this->belongsTo('App\UnidadMedida','unidad_medida_destino_id');
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida_origen_id',
        'unidad_medida_destino_id',
        'factor',
    ];

    protected $table = 'conversion_unidades_medidas';

}
