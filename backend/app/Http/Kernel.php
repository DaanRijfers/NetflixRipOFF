<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'auth.ensure' => \App\Http\Middleware\EnsureAuthenticated::class,
    ];
}