<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Verificar si el usuario NO está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder.');
        }

        // Verificar si el usuario está activo
        $user = Auth::user();
        if (!$user || $user->activo == 0) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Su cuenta no está activa.');
        }

        $response = $next($request);

        // Aplicar headers de no-cache a la RESPONSE
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1970 00:00:00 GMT');
        $response->headers->set('Clear-Site-Data', '"cache", "cookies", "storage"');

        return $response;
    }
}
