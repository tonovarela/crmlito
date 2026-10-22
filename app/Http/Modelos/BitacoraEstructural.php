<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class BitacoraEstructural extends Model
{
    protected $table = 'Bitacora_Estructural';

    protected $primaryKey = 'idBitacoraEstr';

    public $timestamps = false;

    protected $guarded = [
        'idBitacoraEstr',
        'created_at',
        'updated_at'
    ];
}
