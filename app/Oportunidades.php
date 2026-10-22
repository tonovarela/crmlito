<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oportunidades extends Model
{
    protected $table = 'Oportunidades';

    protected $primaryKey = 'idOportunidad';

    public $timestamps = false;

    // protected $fillable = [
    //     'nomOportunidad',
    //     'idCliente',
    //     'idEtapa',
    //     'idEstatus',
    //     'idTipo',
    //     'idExclusion',
    //     'idProbabilidad',
    //     'idAgente',
    //     'Maquila',
    //     'Valor',
    //     'fecCreacion',
    //     'fecRevInicial',
    //     'fecListoCotizar',
    //     'fecCotizando',
    //     'fecRevision',
    //     'fecListoPresupuesto',
    //     'fecAproCliente',
    //     'fecResultadoF',
    //     'fecOrdenConcluida',
    //     'fecUltimoCambio',
    //     'fecCambioElemPre',
    //     'idUsuarioUltCambio',
    //     'numOrdenMTC',
    //     'idUsuarioCreacion',
    //     'notaRetencion',
    //     'idMotivoPerdida',
    // ];

    protected $guarded = [
        'idOportunidad',
        'created_at',
        'updated_at'
    ];
}
