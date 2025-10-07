<?php

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
            'volunteer.auth' => \App\Http\Middleware\VolunteerAuthenticate::class,
            'volunteer.approved' => \App\Http\Middleware\CheckVolunteerApproved::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'volunteer' => \App\Http\Middleware\VolunteerMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'role.session' => \App\Http\Middleware\RoleSession::class,
            'check.ban' => \App\Http\Middleware\CheckTemporaryBan::class,

        ]);
        \Illuminate\Auth\Middleware\Authenticate::redirectUsing(function ($request) {
            if ($user = $request->user()) {
                $role = $user->roles()->first()?->name;

                return match ($role) {
                    'admin' => route('admin.login'),
                    default => route('home'),
                };
            }

            $path = $request->path();
            if (str_starts_with($path, 'admin')) {
                return route('admin.login');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
