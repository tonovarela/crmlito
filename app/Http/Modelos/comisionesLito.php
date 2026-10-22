<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class comisionesLito extends Model
{
    /**
     * Nombre de la tabla principal en la base datos
     * 
     * @var string $table
     */
    protected $table = 'comisionesLito';

    /**
     * Nombre de la llave primaria que esta en la tabla
     * 
     * @var $primaryKey
     */
    protected $primaryKey = 'IdComisionLito';

    /**
     * Deshabilitamos temporalmente los timestamps
     * 
     * @var $timestamps
     */
    public $timestamps = false;


    /**
     * Listado de campos que se encuentran registrados en la tabla
     * 
     * @var array $fillable
     */
    protected $fillable = [
        'IdUsuario',
        'consecutivoIntellisis',
        'comisionModificada',
        'renglonIntellisis',
        'motivo',
        'retencion',
        'estatus',
        'porcentajeModificado',
        'opIntellisis',
        'pedidoIntellisis',
        'firmaVendedor',
        'firmaGerente',
        'firmaDireccion',
        'motivoRechazo',
        'idVendedor',
        'idGerente',
        'idDireccion',
        'idComisionPedido'
    ];
}
