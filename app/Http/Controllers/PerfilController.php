<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->query('m');
        $notify = $request->query('n');

        $perfil = Perfil::find(session('logged.id'));

        if (is_string($mode) && ($mode == 'dim' || $mode == 'light')) {
            $perfil->mode = $mode;
            $perfil->save();

            session()->put('logged.theme', $mode);
            $reload = true;
        }

        if (isset($notify) && ($notify == 1 || $notify == 0)) {
            $perfil->notifications = $notify;
            $perfil->save();

            session()->put('logged.notify', $notify);
            $reload = true;
        }

        return response()->json(['reload' => $reload ?? false]);
    }
}
