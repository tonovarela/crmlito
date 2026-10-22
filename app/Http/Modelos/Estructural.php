<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Estructural extends Model
{
    protected $table = 'Estructural';

    protected $primaryKey = 'folio';

    public $timestamps = false;

    protected $guarded = [
        'folio',
        'created_at',
        'updated_at'
    ];
}
