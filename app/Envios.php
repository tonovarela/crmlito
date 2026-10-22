<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envios extends Model
{
    protected $table = 'Envios';

    protected $primaryKey = 'idEnvio';

    public $timestamps = false;

    protected $guarded = [
        'idEnvio',
        'created_at',
        'updated_at'
    ];
}
