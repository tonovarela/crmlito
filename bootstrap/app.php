<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Las rutas usan la sintaxis 'Controlador@metodo', por eso se mantiene el namespace
        using: function () {
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/candy/elementos_presupuestados.php'));

            Route::prefix('api')
                ->middleware(['api', 'throttle:60,1'])
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->redirectUsersTo('/home');

        $middleware->alias([
            'accessElementosPresupuestados' => \App\Http\Middleware\AccesoElementosPresupuestadosMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('hoy:llamadas')->everyMinute()->timezone('America/Mexico_City');
        $schedule->command('hoy:reuniones1hora')->everyMinute()->timezone('America/Mexico_City');
        $schedule->command('hoy:reuniones')->days([1, 2, 3, 4, 5])->at('08:00')->timezone('America/Mexico_City');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
