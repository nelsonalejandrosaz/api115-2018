<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cliente
 *
 * @property int $id
 * @property int $municipio_id
 * @property string $nombre
 * @property string|null $nombre_comercial
 * @property string|null $telefono_1
 * @property string|null $telefono_2
 * @property string|null $direccion
 * @property int|null $vendedor_id
 * @property string|null $nit
 * @property string|null $nrc
 * @property string|null $giro
 * @property string|null $nombre_contacto
 * @property float $saldo
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Abono[] $abonos
 * @property-read \App\Municipio $municipio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdenPedido[] $ordenes_pedidos
 * @property-read \App\User|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereGiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombreComercial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNombreContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereSaldo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereVendedorId($value)
 * @mixin \Eloquent
 */
class Cliente extends Model
{
    public function ordenes_pedidos()
    {
        return $this->hasMany('App\OrdenPedido');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Municipio');
    }

    public function vendedor()
    {
        return $this->belongsTo('App\User','vendedor_id');
    }

    public function abonos()
    {
        return $this->hasMany('App\Abono');
    }

    protected $fillable = [
        'municipio_id',
        'nombre',
        'nombre_comercial',
        'telefono_1',
        'telefono_2',
        'direccion',
        'vendedor_id',
        'nit',
        'nrc',
        'giro',
        'nombre_contacto',
        'numero_registro',
        'saldo',
    ];

}
