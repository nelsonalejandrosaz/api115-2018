<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TipoDocumento
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Venta[] $ventas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TipoDocumento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoDocumento extends Model
{
    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    protected $table = 'tipo_documentos';
}
