<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class OportunidadesEnv extends Model
{
    protected $table = 'OportunidadesEnv';

    protected $primaryKey = 'idOportunidad';

    public $timestamps = false;

    protected $guarded = [
        'idOportunidad',
        'created_at',
        'updated_at'
    ];
}
