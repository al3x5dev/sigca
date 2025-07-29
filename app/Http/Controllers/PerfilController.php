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

        if (isset($mode)) {
            $perfil->mode = $mode;
            $perfil->save();

            session()->put('logged.theme', $mode);
        }

        if (isset($notify)) {
            $perfil->notifications = $notify;
            $perfil->save();

            session()->put('logged.notify', $notify);
        }
        $referer = $request->header('Referer');

        return redirect()->to($referer);
    }
}
