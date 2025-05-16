<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use App\Services\LdapService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'pass' => 'required|string|min:8',
        ], [
            'user.required' => 'Debe proporcionar un nombre de usuario.',
            'user.string' => 'Nombre de usuario no valido.',
            'pass.required' => 'Debe proporcionar una contraseña.',
            'pass.min' => 'La contraseña no cumple con las politicas de seguridad.',
        ]);

        $user = $request->post('user');
        $pass = $request->post('pass');

        $userDb = Usuario::where('usuario', $user)->first();;

        if (empty($userDb) || $userDb->activo == 0) {
            return back()->withErrors(['credentials' => "$user no es un usuario valido del sistema."]);
        }

        $ldap = LdapService::init($user, $pass);
        try {
            if ($ldap->auth()) {
                $rol = (Rol::find($userDb->rol))->rol;


                $request->session()->put('logged', [
                    'nombre' => $userDb->nombre,
                    'usuario' => $userDb->usuario,
                    'rol'=> $rol
                ]);
                
                return match (strtolower($rol)) {
                    'supervisor' => redirect()->route('admin'),
                    'comprador' => redirect()->route('compra'),
                    'usuario' => redirect()->route('user'),
                    default => redirect()->route('login')
                };
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['credentials' => 'Credenciales incorrectas']);
        }

    }

    public function logout(Request $request)
    {
    }
}
