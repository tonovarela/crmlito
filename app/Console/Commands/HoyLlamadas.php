<?php

namespace App\Console\Commands;

use App\Http\Modelos\Llamadas;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HoyLlamadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hoy:llamadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía listado de las llamadas del día 15 minutos antes de la hora de la llamada';

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
        $fecha = new \DateTime();
        // $llamadas = Llamadas::where('fecInicio', $fecha->format('d-m-Y'))->get();
        $llamadas = DB::select('exec GetLlamadasHoy');

        if ($llamadas != null) {
            foreach ($llamadas as $llamada) {
    
                $message =  'Llamar a ' . Str::limit($llamada->ContactoPrincipal, 17, '...') . ' de ' . Str::limit($llamada->razonsocial, 20, '...') . ' a las ' . $llamada->Hora . ' al ' . $llamada->Telefonos . ' , EXT. ' . $llamada->Extencion . ' para ' . Str::limit($llamada->asuntoLugar, 50, '...');
                $celular_usuario = $llamada->numero_celular;
                $recipient = '{ "msisdn":"' . $celular_usuario . '"}';
                $auth_basic = base64_encode(config('services.labsmobile.user') . ":" . config('services.labsmobile.token'));
                $curl = curl_init();
    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.labsmobile.com/json/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => '{"message":"' .  $message . '", "tpoa":"Sender","recipient":[' . $recipient . ']}',
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Basic " . $auth_basic,
                        "Cache-Control: no-cache",
                        "Content-Type: application/json"
                    ),
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
    
                curl_close($curl);
    
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
            }
        }
    }
}
