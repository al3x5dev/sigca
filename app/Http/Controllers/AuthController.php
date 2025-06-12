<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use App\Services\LdapService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'pass' => 'required',
        ], [
            'user.required' => 'Debe proporcionar un nombre de usuario.',
            'pass.required' => 'Debe proporcionar una contraseña.',
        ]);

        $user = $request->post('user');
        $pass = $request->post('pass');

        $userDb = Usuario::where('usuario', $user)->first();;

        if (empty($userDb) || $userDb->activo == 0) {
            return back()->withErrors(['credentials' => "<b>$user</b> no es un usuario valido del sistema."]);
        }

        $ldap = LdapService::init($user, $pass);
        try {
            if ($ldap->auth()) {

                $rol = (Rol::find($userDb->rol))->rol;

                $request->session()->put('logged', [
                    'id'=> $userDb->id,
                    'nombre' => $userDb->nombre,
                    'usuario' => $userDb->usuario,
                    'rol' => $rol,
                    'last' => $userDb->ultm_acc
                ]);

                // Actualizar el campo fecha_ultimo_acceso con la fecha y hora actual
                $userDb->ultm_acc = Carbon::now(); // O usa ahora() si prefieres
                // Guardar los cambios en la base de datos
                $userDb->save();

                return match ($rol) {
                    'Supervisor' => redirect()->route('admin.dashboard'),
                    'Comprador' => redirect()->route('compra.dashboard'),
                    'Usuario' => redirect()->route('solicitudes.dashboard'),
                    default => redirect()->route('login')
                };
            }
        } catch (\Throwable $th) {
            Log::notice($th->getMessage(), [$userDb->usuario => $userDb->nombre]);
            return back()->withErrors(['credentials' => 'Contraseña incorrecta. Contacte al administrador.']);
        }
    }
}
