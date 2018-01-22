<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Configuracion
 *
 * @mixin \Eloquent
 */
class Configuracion extends Model
{
    protected $fillable = [
        'iva',
        'comisiones',
        'color_tema',
    ];
}
