<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Proveedor
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono_1
 * @property string|null $telefono_2
 * @property string|null $direccion
 * @property string|null $nit
 * @property string|null $nrc
 * @property string|null $nombre_contacto
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Compra[] $compras
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNombreContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereNrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proveedor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Proveedor extends Model
{
    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

    protected $fillable = [
        'nombre',
        'telefono_1',
        'telefono_2',
        'direccion',
        'nit',
        'nrc',
        'nombre_contacto',
    ];

    protected $table = 'proveedores';
}
