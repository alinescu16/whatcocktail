<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Request
    {
        if ( !isset( $_COOKIE['user_identifier'] ) ) {
            setcookie( 'user_identifier', uniqid(), time() + (86400 * 365), '/' );
        } 

        return $next($request);
    }
}
