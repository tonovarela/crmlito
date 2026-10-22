<?php

namespace App\Mail;

use App\Http\Modelos\catPlantillasAlertas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailEstructural extends Mailable
{
    use Queueable, SerializesModels;

    public $alertas;
    public $nombre_proyecto;
    public $folio;
    public $subject;
    public $nombre_agente;
    public $route;
    public $fecha_solicitud;
    public $mensaje;
    public $asignadoA;
    public $motivo_cancelacion;
    public $motivo_cambio_fecha;
    
    public function __construct(catPlantillasAlertas $alertas, $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha)
    {
        $this->alertas = $alertas;
        $this->nombre_proyecto = $nombre_proyecto;
        $this->folio = $folio;
        $this->subject = $subject;
        $this->nombre_agente = $nombre_agente;
        $this->route = $route;
        $this->fecha_solicitud = $fecha_solicitud;
        $this->mensaje = $mensaje;
        $this->asignadoA = $asignadoA;
        $this->motivo_cancelacion = $motivo_cancelacion;
        $this->motivo_cambio_fecha = $motivo_cambio_fecha;
    }

    public function build()
    {
        return $this->view('correos.plantillaMailEstructural')
        ->subject($this->subject);
        // ->bcc('maflores@litoprocess.com');
    }
}
