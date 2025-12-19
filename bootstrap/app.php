<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use ValueResearch\Scaffold\Handlers\KafkaWarningExceptionHandler;
use ValueResearch\Scaffold\Middlewares\RequestExceptionHandlerMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(RequestExceptionHandlerMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            app(KafkaWarningExceptionHandler::class)->handle($e);
        });
    })->create();
