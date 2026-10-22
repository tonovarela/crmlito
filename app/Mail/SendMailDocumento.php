<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailDocumento extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $mensaje;
    public $attachment;
    public $cuenta_agente;
    public $cuenta_destino;
    public $cuenta_cc;
    public $Nombre;
    public $password;
    public $firma;
    public $ruta_firma;
    public $aditionalFiles;
    public $attachmentName;

    public function __construct($subject, $mensaje, $attachment, $cuenta_agente, $cuenta_destino, $cuenta_cc, $Nombre, $password, $firma, $ruta_firma, $aditionalFiles = [], $attachmentName = null)
    {

        $this->subject = $subject;
        $this->mensaje = $mensaje;
        $this->attachment = $attachment;
        $this->cuenta_agente = $cuenta_agente;
        $this->cuenta_destino = $cuenta_destino;
        $this->cuenta_cc = $cuenta_cc;
        $this->Nombre = $Nombre;
        $this->password = $password;
        $this->firma = $firma;
        $this->ruta_firma = $ruta_firma;
        $this->aditionalFiles = $aditionalFiles;
        $this->attachmentName = $attachmentName;
    }

    public function build()
    {
        $correo = $this->view('correos.plantillaMailDocumento')
            ->to($this->cuenta_destino)
            ->from($this->cuenta_agente, $this->Nombre)
            ->cc($this->cuenta_cc)
            ->subject($this->subject)
            ->replyTo($this->cuenta_agente, $this->Nombre);

        if (count($this->aditionalFiles) > 0) {
            foreach ($this->aditionalFiles as $file) {
                $correo->attach($file['path'], ['as' => $file['name']]);
            }
        }
        
        $correo->attach($this->attachment, $this->attachmentName ? ['as' => $this->attachmentName] : []);

        return $correo;
    }
}
