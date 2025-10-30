<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    private const PARENT_PAGE = 'Mis Solicitudes';
    private const URL = 'solicitud.home';

    /**
     * Home vista
     */
    public function index(Request $request)
    {
        /*$items = Solicitud::with(['productos', 'comprador'])
            ->join('SolicitudesHistorico as sh', 'sh.id_solicitud', '=', 'Solicitudes.id')
            ->join('Categorias as c', 'c.id', '=', 'Solicitudes.categoria')
            ->join('Estados as e', 'e.id', '=', 'sh.estado')
            ->select(
                'Solicitudes.*',
                'Solicitudes.id_comprador as comprador',
                'c.tipo as categoria',
                'e.id as estado_id',
                'e.estado as estado',
                'sh.fecha'
            )
            ->orderBy('e.id', 'asc')
            ->orderBy('sh.fecha', 'desc')
            ->paginate(10);*/

        $items = Solicitud::with(['productos', 'comprador'])
            ->join('SolicitudesHistorico as sh', 'sh.id_solicitud', '=', 'Solicitudes.id')
            ->join('Categorias as c', 'c.id', '=', 'Solicitudes.categoria')
            ->join('Estados as e', 'e.id', '=', 'sh.estado')
            ->select([
                'Solicitudes.*',
                'Solicitudes.id_comprador as comprador',
                'c.tipo as categoria',
                'e.id as estado_id',
                'e.estado as estado',
                'sh.fecha'
            ])
            ->whereIn('e.id', function ($query) {
                $query->select('estado')
                    ->from('SolicitudesHistorico')
                    ->whereColumn('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
                    ->orderBy('fecha', 'desc')
                    ->limit(1);
            })->where('Solicitudes.id_usuario',Auth::user()->id)
            ->orderByRaw('e.id ASC, sh.fecha DESC')
            ->paginate(10);

        $data = [
            'page' => [
                'title' => 'solicitud',
                'name' => self::PARENT_PAGE
            ],
            'items' => $items
        ];

        return view('dashboard.solicitud.home', $data);
    }

    /**
     * Vista nueva solicitud
     */
    public function nueva(Request $request)
    {

        $last = Solicitud::orderBy('fecha', 'desc')->pluck('numero')->first();
        $almacenes = DB::connection('une_2316a_int')->select('SELECT * FROM vw_SIGCA_Almacenes');
        $categoria = $request->query('categoria', false);

        $categoriasId = Categoria::all('id')->pluck('id')->toArray();

        if (!$categoria || !in_array($categoria, $categoriasId)) {
            abort(404);
        }

        // Calcula # solicitud
        if (!empty($last)) {
            $num = explode('/', $last);
            $sum = ($num[1] == date('Y'))
                ? $num[0] + 1
                : 1;
        } else {
            $sum = 1;
        }


        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => 'Nueva Solicitud'
            ],
            'solicitud' => [
                'numero' => $sum . '/' . date('Y')
            ],
            'categoria' => $categoria,
            'almacenes' => $almacenes
        ];
        return view('dashboard.solicitud.nueva', $data);
    }

    /**
     * Crea solicitud
     */
    public function addSolicitud(Request $request)
    {
        $productos = json_decode($request->post('productos'), true);

        if (empty($productos)) {
            return <<<HTML
            <div class="alert alert-error" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                <span >No hay productos para agregar a la solicitud</span>
            </div>
            HTML;
        }

        try {
            //Crear Solicitud
            $newSolicitud = new Solicitud();
            $newSolicitud->fill([
                'numero' => $request->post('numero'),
                'id_usuario' => $request->post('usuario'),
                'categoria' => $request->post('categoria')
            ]);
            if ($newSolicitud->save()) {
                $lastSolicitud = Solicitud::where('numero', $request->post('numero'))->first();
                $save = [];
                //Agregar productos
                foreach ($productos as $producto) {
                    $id = $producto['Id_Producto'] ?? uniqid('ID_');

                    $addProducto = new Producto();
                    $addProducto->fill([
                        'id_solicitud' => $lastSolicitud->id,
                        'id_producto' => $id,
                        'descripcion' => $producto['Desc_Producto'],
                        'cant_solicitada' => $producto['Cantidad'],
                        'nuevo' => str_starts_with($id, 'ID_')
                    ]);
                    $save[] = $addProducto->save();
                }

                // Verificar si todos los registros se agregaron correctamente
                $allSavedSuccessfully = count($save) === count($productos) && !in_array(false, $save);

                if ($allSavedSuccessfully) {
                    $route = route('solicitud.home');
                    return <<<HTML
            <div class="alert alert-success" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                <span >Todos los productos se han agregado correctamente a la base de datos.</span>
                <script>
                    setTimeout(() => {
                        window.location.assign('{$route}');
                    }, 1000);
                </script>
            </div>
            HTML;
                    return;
                } else {
                    return "Hubo un error al agregar algunos productos a la base de datos.";
                }
            }
        } catch (\Throwable $th) {
            return <<<HTML
            <div class="alert alert-error" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                <span hidden>Error al insertar la nueva solicitud en la base de datos</span>
                <span>{$th->getMessage()}</span>
            </div>
            HTML;
        }
    }

    /**Elimina solicitud */
    public function destroy($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        return response(null, 200);
    }
}
