<?php

namespace App\Mail;

use App\Http\Modelos\catPlantillasAlertas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alertas;
    public $nombre_oportunidad;
    public $folio;
    public $fecha;
    public $estatus_oportunidad;
    public $subject;
    public $nombre_agente;
    public $route;
    public $nota;
    public $fecha_solicitud;
    public $fecha_asignacion;
    public $fecha_entrega;
    public $fecha_aut_archivo;
    public $diasEntrega;
    public $mensaje;
    public $numOrdenMTC;
    public $rejectDocument;

    public function __construct(catPlantillasAlertas $alertas, $nombre_oportunidad, $folio, $fecha, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument = 0)
    {
        $this->alertas = $alertas;
        $this->nombre_oportunidad = $nombre_oportunidad;
        $this->folio = $folio;
        $this->fecha = $fecha;
        $this->estatus_oportunidad = $estatus_oportunidad;
        $this->subject = $subject;
        $this->nombre_agente = $nombre_agente;
        $this->route = $route;
        $this->nota = $nota;
        $this->fecha_solicitud = $fecha_solicitud;
        $this->fecha_asignacion = $fecha_asignacion;
        $this->fecha_entrega = $fecha_entrega;
        $this->fecha_aut_archivo = $fecha_aut_archivo;
        $this->diasEntrega = $diasEntrega;
        $this->mensaje = $mensaje;
        $this->numOrdenMTC = $numOrdenMTC;
        $this->rejectDocument = $rejectDocument;
    }

    public function build()
    {
        return $this->view('correos.plantillaMail')
        ->subject($this->subject);
        // ->bcc('maflores@litoprocess.com');
    }
}
