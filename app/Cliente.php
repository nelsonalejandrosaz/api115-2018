<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cliente
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenesPedidos
 * @mixin \Eloquent
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono1
 * @property string|null $telefono2
 * @property string|null $direccion
 * @property string|null $nombreContacto
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombreContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereUpdatedAt($value)
 */
class Cliente extends Model
{
    public function ordenesPedidos()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    protected $fillable = [
        'nombre',
        'telefono1',
        'telefono2',
        'direccion',
        'nombreContacto',
    ];

//    protected $table = 'clientes';
}
