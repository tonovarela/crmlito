<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class BitacoraComisionesLito extends Model
{
    /**
     * Nombre de la tabla
     * 
     * @var string $table
     */
    protected $table = 'BitacoraComisionesLito';

    /**
     * Llave primaria de la tabla
     * 
     * @var string $primaryKey
     */
    protected $primaryKey = 'idBitacoraComisiones';

    /**
     * Deshabilitamos temporalmente los timestamps
     * 
     * @var $timestamps
     */
    public $timestamps = false;

    /**
     * Campos de la tabla
     * 
     * @var array $protected
     */
    protected $fillable = [
        'section',
        'previousChange',
        'newChange',
        'userId',
        'comisionLitoId',
    ];
}
