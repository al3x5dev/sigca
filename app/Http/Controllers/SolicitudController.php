<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    /**
     * Home vista
     */
    public function dashboard(Request $request)
    {

        $items = Solicitud::with(['productos', 'comprador'])
            ->join('SolicitudesHistorico as sh', 'Solicitudes.id', '=', 'sh.id_solicitud')
            ->join('Estados as e', 'sh.estado', '=', 'e.id')
            ->join(DB::raw('(SELECT id_solicitud, MAX(estado) as max_estado FROM SolicitudesHistorico GROUP BY id_solicitud) as max_sh'), function ($join) {
                $join->on('sh.id_solicitud', '=', 'max_sh.id_solicitud')
                    ->on('sh.estado', '=', 'max_sh.max_estado');
            })
            ->select(
                'Solicitudes.*',
                'sh.fecha',
                'e.id as estado_id',
                'e.estado',
                'sh.estado as historico_estado'
            )
            ->where('Solicitudes.id_usuario', session('logged.id'))
            ->get();

        $pendiente = 0;
        $completado = 0;
        $proceso = 0;
        $eliminado = 0;

        foreach ($items as $item) {
            if ($item->id_usuario == session('logged.id')) {
                switch ($item->estado_id) {
                    case 1:
                        $pendiente++;
                        break;
                    case 3:
                        $completado++;
                        break;
                    case 2:
                        $proceso++;
                        break;
                    case 4:
                        $eliminado++;
                        break;
                }
            }
        }

        $data = [
            'page' => [
                'title' => 'dashboard',
                'name' => 'Panel de Control'
            ],
            'estado' => [
                'pendiente' => $pendiente,
                'completado' => $completado,
                'proceso' => $proceso,
                'eliminado' => $eliminado,
            ],
            'items' => $items
        ];
        return view('dashboard.user.home', $data);
    }

    public function index(Request $request)
    {
        $data = [
            'page' => [
                'title' => 'userSolicitudes',
                'name' => 'Solicitudes'
            ]
        ];
        return view('dashboard.user.solicitudes', $data);
    }

    /**
     * Vista nueva solicitud
     */
    public function add()
    {

        $last = Solicitud::orderBy('fecha', 'desc')->pluck('numero')->first();

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
                'title' => 'newSolicitud',
                'name' => 'Nueva Solicitud'
            ],
            'solicitud' => [
                'numero' => $sum . '/' . date('Y')
            ],
        ];
        return view('dashboard.user.add', $data);
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
                'id_usuario' => $request->post('usuario')
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
                    return <<<HTML
            <div class="alert alert-success" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                <span >Todos los productos se han agregado correctamente a la base de datos.</span>
                <script>
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
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
