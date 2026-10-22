<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class TiempoEntregaOportunidades extends Model
{
    protected $table = 'TiempoEntregaOportunidades';

    protected $primaryKey = 'idTiempoEntregaOprotunidad';

    public $timestamps = false;

    protected $guarded = [
        'idTiempoEntregaOprotunidad',
        'created_at',
        'updated_at'
    ];

}
