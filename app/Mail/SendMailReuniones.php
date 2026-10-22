<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailReuniones extends Mailable
{
    use Queueable, SerializesModels;

    public $reuniones;
    public $subject;
    public $nombre;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reuniones, $subject, $nombre)
    {
        $this->reuniones = $reuniones;
        $this->subject = $subject;
        $this->nombre = $nombre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('correos.reuniones-hoy')
        ->subject($this->subject);
    }
}
