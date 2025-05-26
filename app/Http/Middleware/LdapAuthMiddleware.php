<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LdapAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('logged') && !$request->routeIs('signin')) {
            //Log::info('Redirigiendo a login porque la sesión no está establecida.');
            return redirect()->route('login');
        }

        $response = $next($request);

        $request->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $request->headers->set('Pragma', 'no-cache');
        $request->headers->set('Expires', 'Fri, 01 Jan 1970 00:00:00 GMT');
        $request->headers->set('Clear-Site-Data', '"cache", "cookies", "storage"');

        return $response;
    }
}
