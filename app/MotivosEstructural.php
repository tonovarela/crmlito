<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivosEstructural extends Model
{
    protected $table = 'motivosEstructural';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'motivo',
        'estatus',
        'ticket_id',
        'usuario_id',
        'fecha_registro',
    ];
}
