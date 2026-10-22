<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'Bitacora';

    protected $primaryKey = 'idBitacora';

    public $timestamps = false;

    protected $guarded = [
        'idBitacora',
        'created_at',
        'updated_at'
    ];

}
