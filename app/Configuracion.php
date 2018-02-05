<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = [
        'iva',
        'comisiones',
        'color_tema',
    ];

    protected $table = 'configuraciones';
}
