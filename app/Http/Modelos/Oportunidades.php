<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Oportunidades extends Model
{
    protected $table = 'Oportunidades';

    protected $primaryKey = 'idOportunidad';

    public $timestamps = false;

    protected $guarded = [
        'idOportunidad',
        'created_at',
        'updated_at'
    ];
}
