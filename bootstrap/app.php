<?php

use Cog\Laravel\Ban\Http\Middleware\ForbidBannedUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\AuthenticateSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', [AuthenticateSession::class]);

        $middleware->alias(['forbid-banned-user' => ForbidBannedUser::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
