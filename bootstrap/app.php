<?php

<<<<<<< HEAD
=======
use App\Http\Middleware\EnsureUserHasStaffRole;
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
<<<<<<< HEAD
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
=======
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->alias([
<<<<<<< HEAD
            'staff' => \App\Http\Middleware\EnsureUserHasStaffRole::class,
=======
            'staff' => EnsureUserHasStaffRole::class,
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
