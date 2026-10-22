<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Url del web service
    |--------------------------------------------------------------------------
    |
    | Este valor es la url del web service que se necesita consumir, 
    | este ws se consume para realizar la validación de si se pasa o no a producción una tripa
    |
    */

    'url' => env('WS_MC_URL', ''),
];