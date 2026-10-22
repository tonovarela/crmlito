<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class RetencionOportunidadesEnv extends Model
{
    protected $table = 'RetencionOportunidadesEnv';

    protected $primaryKey = 'idRetencioOprotunidadEnvio';

    public $timestamps = false;

    protected $guarded = [
        'idRetencioOprotunidadEnvio',
        'created_at',
        'updated_at'
    ];
}
