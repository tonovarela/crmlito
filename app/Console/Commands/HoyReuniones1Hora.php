<?php

namespace App\Console\Commands;

use App\Mail\SendMailReuniones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HoyReuniones1Hora extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hoy:reuniones1hora';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía listado de las reuniones del día 1 hora antes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subject = 'REUNIONES PARA HOY';
        
        $reuniones = DB::select('exec GetReunionesHoy1Hora');
        foreach ($reuniones as $reunion) {
            $reuniones_usuario = DB::select('exec GetReunionesHoyXUsuario ?', [
                $reunion->id_usuario,
            ]);
            
            $mail_to = $reunion->Correo;
            // Mail::to($mail_to)->cc('gcasas@litoprocess.com')->send(new SendMailReuniones($reuniones_usuario, $subject, $reunion->Nombre ));
            Mail::to($mail_to)->send(new SendMailReuniones($reuniones_usuario, $subject, $reunion->Nombre ));
        }
    }
}
