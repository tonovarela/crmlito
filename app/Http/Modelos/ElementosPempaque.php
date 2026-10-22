<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPempaque extends Model
{
    protected $table = 'ElementosPempaque';
    protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = ['idElementoP','idTipoEmpaque','obsEmpaque'];
}
