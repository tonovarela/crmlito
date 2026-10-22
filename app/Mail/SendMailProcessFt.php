<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailProcessFt extends Mailable
{
    use Queueable, SerializesModels;

    public $folio;
    public $nomProductoFicha;
    public $usuarioNombre;
    public $nomCliente;

    public function __construct($folio, $nomProductoFicha, $usuarioNombre, $nomCliente)
    {
        $this->folio = $folio;
        $this->nomProductoFicha = $nomProductoFicha;
        $this->usuarioNombre = $usuarioNombre;
        $this->nomCliente = $nomCliente;
    }

    public function build()
    {
        return $this->view('correos.plantillaMailProcess')
        ->subject('Envió de solicitud de generación de ficha técnica');
    }
}
