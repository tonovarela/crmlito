<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPacabados extends Model
{
    protected $table = 'ElementosPacabados';
    // protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = [
       'idElementoP'
      ,'numInterior'
      ,'conDoblado'
      ,'conPerforado'
      ,'conPersonalizado'
      ,'conSuajado'
      ,'conPegado'
      ,'conPlecado'
      ,'conOtro'
      ,'descOtro'
      ,'conGrabado'
      ,'conHotStamping'
      ,'conGatefold'
      ,'idTipoGatefold'
    ];
}
