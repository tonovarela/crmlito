<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPacabadosGral extends Model
{
    protected $table = 'ElementosPacabadosGral';
    protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = ['idElementoP','idAcabado'];
}
