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
    ->withMiddleware(function (Middleware $middleware): void {
     $middleware ->appendToGroup(
         'two_factor',[\App\Http\Middleware\EnsureTwoFactorIsVerified::class]
     );
     $middleware->appendToGroup(
         'prevent-back-history',[\App\Http\Middleware\PreventBackHistory::class]
     );
        $middleware->appendToGroup(
            'track-user-activity',[\App\Http\Middleware\TrackUserActivity::class]
        );


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
