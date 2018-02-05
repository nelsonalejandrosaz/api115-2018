<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    protected $fillable = [
        'venta_id',
        'cliente_id',
        'fecha',
        'detalle',
        'cantidad',
        'forma_pago_id',
    ];

    protected $dates = [
        'fecha',
    ];
}
