<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class catRequisitos extends Model
{
    protected $table = 'catRequisitos';
    protected $primaryKey = 'idRequisito';
    public $timestamps = false;

    protected $fillable = [
        'descRequisito',
        'estatusRequisito'
    ];
}
