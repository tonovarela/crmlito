<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // La app se publica por IP local y por dominio público: en peticiones web las URLs salen
        // de las cabeceras X-Forwarded-Host/Proto/Prefix del proxy (trustProxies). En consola
        // (scheduler, correos) no hay petición, así que se usa APP_URL.
        if ($this->app->runningInConsole() && $appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);

            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
