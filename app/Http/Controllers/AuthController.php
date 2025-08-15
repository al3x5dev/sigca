<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\LdapService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $userDb = Usuario::with(['rol', 'perfil'])->where('usuario', $user)->first();

        if (empty($userDb) || $userDb->activo == 0) {
            return back()->withErrors(['credentials' => "<b>$user</b> no es un usuario valido del sistema."]);
        }

        $login = ($user == 'admin')
            ? Hash::check($pass, $userDb->password)
            : (LdapService::init($user, $pass))->auth();

        try {
            if ($login) {
                // Actualizar el campo fecha_ultimo_acceso con la fecha y hora actual
                $userDb->ultm_acc = Carbon::now(); // O usa ahora() si prefieres
                // Guardar los cambios en la base de datos
                $userDb->save();

                Auth::login($userDb);

                return redirect()->route('dashboard');
            }
        } catch (\Throwable $th) {
            Log::notice($th->getMessage(), [$userDb->usuario => $userDb->nombre]);
            return back()->withErrors(['credentials' => 'Contraseña incorrecta. Contacte al administrador.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
