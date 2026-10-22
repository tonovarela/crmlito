<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AsignacionCotizacion extends Mailable
{
    use Queueable, SerializesModels;

    public $folioOp;
    public $nomOport;
    public $numCot;

    public function __construct($folioOp, $nomOport, $numCot)
    {
        $this->folioOp = $folioOp;
        $this->nomOport = $nomOport;
        $this->numCot = $numCot;
    }

    public function build()
    {
        return $this->view('correos.plantillaMailAsignCot')
        ->subject('Cotización aprobada');
    }
}
