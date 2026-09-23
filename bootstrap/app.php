<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustHosts();
        $middleware->append(SecurityHeaders::class);
        $middleware->alias([
            'setlocale' => SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // No component ships a Livewire JS or CSS module: any request for one is a 404,
        // not an error whose stack trace an anonymous caller can write to the log at will
        $exceptions->map(function (Throwable $e): Throwable {
            if (! request()->is('livewire-*/js/*', 'livewire-*/css/*')) {
                return $e;
            }

            return new NotFoundHttpException(previous: $e);
        });
    })->create();
