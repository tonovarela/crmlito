<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class RetencionOportunidades extends Model
{
    protected $table = 'RetencionOportunidades';

    protected $primaryKey = 'idRetencioOprotunidad';

    public $timestamps = false;

    protected $guarded = [
        'idRetencioOprotunidad',
        'created_at',
        'updated_at'
    ];
}
