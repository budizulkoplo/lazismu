<?php

use App\Http\Middleware\GlobalApp;
use App\Http\Middleware\CheckActiveProject;
use App\Http\Middleware\EnsureMuzakiAuthenticated;
use App\Http\Middleware\PreventSearchIndexing;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftar alias middleware
        $middleware->alias([
            'global.app'    => GlobalApp::class,
            'role'          => RoleMiddleware::class,
            'check.project' => CheckActiveProject::class,
            'muzaki.auth'   => EnsureMuzakiAuthenticated::class,
        ]);

        $middleware->appendToGroup('web', [
            PreventSearchIndexing::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
