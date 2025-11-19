<?php

namespace App\Http\Controllers;

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
            'usuarios' => Usuario::paginate(10)
        ];

        //dd($this->getData(request()));

        return view('dashboard.admin.users', $data);
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
