<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ElementosPresupuestados extends Model
{
    // protected $guarded=[];
    protected $table = 'ElementosP';
    protected $primaryKey = 'idElementoP';

    public $timestamps = false;
    protected $fillable = [
        'idTipoItem'
      ,'nomElementoP'
      ,'cant1'
      ,'cant2'
      ,'cant3'
      ,'cant4'
      ,'numPresupuesto'
      ,'fecTopeCot'
      ,'fecReqEntProd'
      ,'esNuevo'
      ,'preObjetivo'
      ,'preCompetencia'
      ,'nomCompetencia'
      ,'idEstadoItem'
      ,'porcMargen'
      ,'porcFinanciamiento'
      ,'porcComisionExt'
      ,'porcComisionInt'
      ,'idTipoPreprensa'
      ,'conRetoque'
      ,'idTipoEncuadernado'
      ,'otroEncuadernado'
      ,'obsEncuadernado'
      ,'conFullfilment'
      ,'fecRegistro'
      ,'idUsuarioRegistro'
      ,'idTipoDistribucion'
      ,'numPresupuestoMTC'
    ];
}
