<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // Vous pouvez également ajouter des middlewares web globaux ici si nécessaire, par exemple :
        // $middleware->web(append: [
        //     \App\Http\Middleware\HandleInertiaRequests::class, // si vous utilisez Inertia.js
        // ]);
    }) // Cette parenthèse ferme correctement la closure withMiddleware
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create(); // Ces méthodes sont chaînées à l'appel de Application::configure(), pas à l'intérieur de withMiddleware
