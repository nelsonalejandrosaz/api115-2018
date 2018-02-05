<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
