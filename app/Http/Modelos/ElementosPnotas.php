<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPnotas extends Model
{
    // protected $guarded=[];
    protected $table = 'ElementosPnotas';
    protected $primaryKey = 'idNota';

    public $timestamps = false;
    protected $fillable = [
      'idElementoP'
      ,'Nota'
      ,'FechaRegistro'
      ,'idUsuarioRegistro'
    ];
}
