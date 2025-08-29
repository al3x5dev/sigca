<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    private const THEMES = ['light', 'dark', 'corporate', 'business', 'synthwave', 'emerald', 'dim', 'forest', 'fantasy', 'lofi', 'wireframe', 'black', 'dracula', 'cymk', 'night', 'winter', 'dim', 'nord'];

    public function index(Request $request)
    {
        $theme = $request->query('m');
        $notify = $request->query('n');

        $perfil = Perfil::find(Auth::id());

        if (is_null($perfil)) {
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }
        if (isset($theme) && (in_array($theme, self::THEMES))) {
            $perfil->theme = $theme;
            $perfil->save();

            $reload = true;
        }

        if (isset($notify) && ($notify == 1 || $notify == 0)) {
            $perfil->notifications = $notify;
            $perfil->save();

            $reload = true;
        }

        return response()->json(['reload' => $reload ?? false]);
    }
}
