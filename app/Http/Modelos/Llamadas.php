<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Llamadas extends Model
{
    protected $table = 'LlamadasReuniones';

    protected $primaryKey = 'idLlamadasReuniones';

    public $timestamps = false;

    protected $guarded = [
        'idLlamadasReuniones',
        'created_at',
        'updated_at'
    ];
}
