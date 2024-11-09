<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\LoginCheck;
use App\Http\Middleware\DashboardCheck;
use App\Http\Middleware\CheckRoute;
use App\Http\Middleware\ChangePassLoginChaeck;
use App\Http\Middleware\LockCheck;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'logincheck' => LoginCheck::class,
            'DashboardCheck' => DashboardCheck::class,
            'CheckRoute' => CheckRoute::class,
            'ChangePassLoginChaeck' => ChangePassLoginChaeck::class,
            'LockCheck' => LockCheck::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
