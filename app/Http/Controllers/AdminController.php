<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private const PARENT_PAGE = 'Administración';
    private const URL = 'admin.home';

    public function index(Request $request)
    {
        $data = [
            'page' => [
                'title' => 'admin',
                'name' => 'Panel de Administración'
            ]
        ];
        return view('dashboard.admin.home', $data);
    }

    public function users()
    {

        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => 'Gestión de Usuarios'
            ],
            'usuarios' => Usuario::all()
        ];

        return view('dashboard.admin.users', $data);
    }

    public function userOptions($id)
    {
        $user = Usuario::with(['rol'])->findOrFail($id);

        //dd($user);

        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => 'Gestión de Permisos'
            ],
            'usuario' => $user,
            'roles'=>Rol::all(),
            'categorias'=>Categoria::all()
        ];

        return view('dashboard.admin.user-options', $data);
    }



    /**
     * Retorna los datos paginados y filtrados en formato JSON.
     * Este es el endpoint que llamará Alpine.js.
     */
    public function getData(Request $request)
    {
        $search = $request->get('search');

        $usuario = Usuario::query()
            // Aplicamos el filtro de búsqueda solo si 'search' tiene valor.
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
            })
            // Paginamos los resultados. 10 es un buen número para empezar.
            ->paginate(10);

        // Laravel convierte automáticamente el paginador a JSON.
        // Incluye: data, current_page, last_page, per_page, links, etc.
        return response()->json($usuario);
    }
}
