<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class v_Oportunidades extends Model
{
    protected $table = 'v_Oportunidades';

    protected $primaryKey = 'idOportunidad';

    public $timestamps = false;

    protected $guarded = [
        'idOportunidad',
    ];
}
