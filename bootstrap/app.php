<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([

            'role' => \App\Http\Middleware\RoleMiddleware::class,
            // check shift kasir
            'check.shift' => \App\Http\Middleware\CheckOpenShift::class,
        ]);

        // 2. Tambahkan middleware modul ke dalam grup 'web' secara global
        $middleware->web(append: [
            \App\Http\Middleware\CheckModuleAccess::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
