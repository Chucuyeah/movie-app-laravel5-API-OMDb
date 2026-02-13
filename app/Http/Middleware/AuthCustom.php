<?php

namespace App\Http\Middleware;

use Closure;

class AuthCustom
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
