<?php


namespace App\Http\Middleware;

use Closure;

class ForceChangePassword
{
    public function handle($request, Closure $next)
    {
        if ($request->query('forceChangePass') == 1) {
            return $next($request);
        }

        if (isset($_COOKIE['is_first_login']) && $_COOKIE['is_first_login'] == 1) {
            return redirect('/profile?forceChangePass=1');
        }

        return $next($request);
    }
}

