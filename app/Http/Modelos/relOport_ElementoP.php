<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class relOport_ElementoP extends Model
{
    protected $table = 'relOport_ElementoP';
    protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = ['idElementoP','fecRelacion','idUsuarioRelaciona'];
}
