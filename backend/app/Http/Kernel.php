<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'jwt' => \App\Http\Middleware\JwtMiddleware::class,
    ];
}