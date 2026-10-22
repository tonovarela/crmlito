<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class EncuestaEnvios extends Model
{
    protected $table = 'EncuestaEnvios';

    protected $primaryKey = 'idEncuestaEnvio';

    public $timestamps = false;

    protected $guarded = [
        'idEncuestaEnvio',
        'created_at',
        'updated_at'
    ];
}
