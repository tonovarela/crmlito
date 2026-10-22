<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Tests\TestCase;

/**
 * Recorre las rutas GET sin parámetros con un usuario real y verifica que ninguna
 * devuelva un error 500. Requiere una BD de pruebas y SMOKE_LOGIN con el login del
 * usuario a usar:
 *
 *   docker compose exec -e SMOKE_LOGIN=usuario app php artisan test --filter=SmokeTest
 */
class SmokeTest extends TestCase
{
    // Rutas que modifican datos, cierran sesión o dependen de servicios externos
    private const EXCLUIDAS = [
        'logout',
        'clear-cache',
        'up',
        'apiOportunidadCambios',
        'tripaCCRealizada',
        'tripaCCorreccioneRealizada',
        'obtener-checklist-op',
        'oportunidad-realizado-estatus',
    ];

    public function test_rutas_get_no_devuelven_500(): void
    {
        $login = env('SMOKE_LOGIN');

        if (! $login) {
            $this->markTestSkipped('Define SMOKE_LOGIN para correr la prueba de humo.');
        }

        $user = User::where('login', $login)->firstOrFail();

        $rutas = collect(RouteFacade::getRoutes()->getRoutes())
            ->filter(fn (Route $route) => in_array('GET', $route->methods()))
            ->filter(fn (Route $route) => ! str_contains($route->uri(), '{'))
            ->filter(fn (Route $route) => ! str_starts_with($route->uri(), '_debugbar'))
            ->reject(fn (Route $route) => in_array($route->uri(), self::EXCLUIDAS));

        $errores = [];

        foreach ($rutas as $route) {
            $response = $this->actingAs($user)
                ->withSession(['usuario' => $login])
                ->get('/'.ltrim($route->uri(), '/'));

            if ($response->getStatusCode() >= 500) {
                $errores[] = $route->uri().' -> '.$response->getStatusCode();
            }
        }

        $this->assertSame([], $errores, "Rutas con error 500:\n".implode("\n", $errores));
    }
}
