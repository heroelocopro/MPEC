<?php

use App\Console\Commands\VerificarPeriodos;
use App\Http\Middleware\VerificarRol;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => \App\Http\Middleware\VerificarRol::class]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:verificar-periodos')->daily();
        $schedule->command('app:generar-notas-finales')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
            // Manejar error de conexión MySQL
    $exceptions->render(function (PDOException $e, Request $request) {
        if (str_contains($e->getMessage(), 'SQLSTATE[HY000] [2002]')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '⚙️ La base de datos está arrancando. Intenta nuevamente en unos segundos.'
                ], 503);
            }

            return response(
                '<h1>⚙️ La base de datos está arrancando...</h1>
                 <p>Por favor, intenta nuevamente en unos segundos.</p>',
                503
            );
        }

        return null; // Usa el render por defecto para otros errores
    });
    })->create();
