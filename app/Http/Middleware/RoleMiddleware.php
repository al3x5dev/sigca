<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$rol): Response
    {
        $userRol = strtolower($request->session()->get('logged.rol', ''));
        // Verificar si el usuario está logueado
        if (!$userRol) {
            return redirect()->route('login');
        }
        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($userRol, $rol)) {
            // Redirigir a la ruta correspondiente según el rol
            return match ($userRol) {
                'Supervisor' => redirect()->route('admin.dashboard'),
                'Comprador' => redirect()->route('compra.dashboard'),
                'Usuario' => redirect()->route('solicitudes.dashboard'),
                default => redirect()->route('login')
            };
        }

        return $next($request);
    }
}
