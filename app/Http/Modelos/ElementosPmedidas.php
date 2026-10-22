<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPmedidas extends Model
{
    protected $table = 'ElementosPmedidas';
    protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = [
        'idElementoP'
        ,'numInterior'
        ,'largoExtendido'
        ,'anchoExtendido'
        ,'largoFinal'
        ,'anchoFinal'
        ,'numPaginas'
        ,'idTipoPapel'
        ,'PapelEspecial'
        ,'numTintasF'
        ,'numTintasV'
        ,'barniz'
        ,'idTipoBarniz'
        ,'numCarasLaminado'
        ,'idTipoLaminado'
        ,'nombreInterior'
        ,'TipoPapelgrs'
    ];
}
