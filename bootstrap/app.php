<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'isTeacher' => \App\Http\Middleware\IsTeacher::class,
            'isStudent' => \App\Http\Middleware\IsStudent::class,
            'roleDashboard' => \App\Http\Middleware\RedirectLoginToRoleDashboard::class,
        ]);
        $middleware->web(append: [SetLocale::class]);
        $middleware->appendToGroup('web', \App\Http\Middleware\RedirectLoginToRoleDashboard::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
