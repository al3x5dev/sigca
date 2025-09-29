<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompradorController extends Controller
{
    private const PARENT_PAGE = 'Gestión de Solicitudes';
    private const URL = 'gestion.home';
    private const ESTADOS = [
        'aprobada' => 2,
        'completada' => 3,
        'cancelada' => 4
    ];

    public function index(Request $request)
    {
        $comprador = Auth::user()->id;
        /*
        $items = Solicitud::with(['usuario','ultimoEstado.estado'])
            ->join('SolicitudesHistorico as sh', 'sh.id_solicitud', '=', 'Solicitudes.id')
            ->join('Categorias as c', 'c.id', '=', 'Solicitudes.categoria')
            ->join('Estados as e', 'e.id', '=', 'sh.estado')
            ->leftJoin('CompradoresCategorias as cc', 'cc.id_categoria', '=', 'c.id')
            ->select(
                'Solicitudes.*',
                'Solicitudes.id_comprador as comprador',
                'c.tipo as categoria',
                'e.id as estado_id',
                'e.estado as estado',
                'sh.fecha'
            )
            ->where('Solicitudes.id_comprador', $comprador)
            ->orWhereNull('Solicitudes.id_comprador')
            ->whereIn('e.id', function ($query) {
                $query->select('estado')
                    ->from('SolicitudesHistorico')
                    ->whereColumn('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
                    ->orderBy('SolicitudesHistorico.fecha', 'desc')
                    ->limit(1);
            })
            ->orderBy('e.id', 'asc')
            ->orderBy('sh.fecha', 'desc')
            ->paginate(10);
*/


        $items = Solicitud::with(['usuario'])
            ->join('SolicitudesHistorico as sh', 'sh.id_solicitud', '=', 'Solicitudes.id')
            ->join('Categorias as c', 'c.id', '=', 'Solicitudes.categoria')
            ->join('Estados as e', 'e.id', '=', 'sh.estado')
            ->leftJoin('CompradoresCategorias as cc', 'cc.id_categoria', '=', 'c.id')
            ->select([
                'Solicitudes.*',
                'Solicitudes.id_comprador as comprador',
                'c.tipo as categoria',
                'e.id as estado_id',
                'e.estado as estado',
                'sh.fecha'
            ])
            ->where(function ($query) use ($comprador) {
                $query->where('Solicitudes.id_comprador', $comprador)
                    ->orWhereNull('Solicitudes.id_comprador');
            })
            ->where('cc.id_comprador',$comprador)
            ->whereIn('e.id', function ($query) {
                $query->select('estado')
                    ->from('SolicitudesHistorico')
                    ->whereColumn('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
                    ->orderBy('fecha', 'desc')
                    ->limit(1);
            })
            ->orderByRaw('e.id ASC, sh.fecha DESC')
            ->paginate(10);


        $data = [
            'page' => [
                'title' => 'comprador',
                'name' => self::PARENT_PAGE
            ],
            'items' => $items,
        ];
        return view('dashboard.gestor.home', $data);
    }

    public function estado($anno, $numb)
    {
        $n = implode('/', [$numb, $anno]);

        //Devuelve 404 si no existe el registro
        Solicitud::where('numero', $n)->firstOrFail();

        $items = Solicitud::with(['usuario', 'productos'])
            ->join('SolicitudesHistorico as sh', 'sh.id_solicitud', '=', 'Solicitudes.id')
            ->join('Categorias as c', 'c.id', '=', 'Solicitudes.categoria')
            ->join('Estados as e', 'e.id', '=', 'sh.estado')
            ->select(
                'Solicitudes.*',
                //'Solicitudes.id_comprador as comprador',
                'c.tipo as categoria',
                'e.id as estado_id',
                'e.estado as estado',
                'sh.fecha'
            )
            ->whereIn('e.id', function ($query) {
                $query->select('estado')
                    ->from('SolicitudesHistorico')
                    ->whereColumn('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
                    ->orderBy('fecha', 'desc')
                    ->limit(1);
            })
            ->where('Solicitudes.numero', $n)
            ->get();

        //dd($items);

        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => "Solicitud $n"
            ],
            'id' => $items[0]->id,
            'state' => $items[0]->estado,
            'items' => $items
        ];

        return view('dashboard.gestor.estado', $data);
    }

    public function changeState($id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $tipo = request()->input('type');


        // recupera cuerpo de solicitud
        $data = json_decode(request()->input('productos'), true);

        //APROBAR SOLICITUD
        if ($tipo == 'aprobar' && is_null($data)) {
            $solicitud->id_comprador = Auth::user()->id;
            $solicitud->save();

            $history = new SolicitudHistorico();
            $history->id_solicitud = $id;
            $history->estado = self::ESTADOS['aprobada'];
            $history->save();

            return <<<HTML
            <div class="alert alert-success" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" /><path d="M14 19l2 2l4 -4" /><path d="M9 8h4" /><path d="M9 12h2" /></svg>
                <span >Solicitud aprobada</span>
            </div>
            <script>
                    setTimeout(() => {
                        window.history.back();
                    }, 1200);
                </script>
            HTML;
        }


        //dd($data, $solicitud->productos);


        //ACTUALIZAR SOLICITUD
        if ($tipo == 'actualizar') {
            if (empty($data)) {
                return <<<HTML
                <div class="alert alert-error" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                <span >No se han detectado cambios para actualizar esta solicitud</span>
                </div>
                HTML;
            }

            //Verificar comprador
            /*if ($solicitud->comprador->id != Auth::user()->id) {
                return <<<HTML
                <div class="alert alert-error" x-bind="toggle=true">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                <span ></span>
                </div>
                HTML;
            }*/

            foreach ($solicitud->productos as $producto) {
                foreach ($data as $value) {


                    if (
                        $producto->id_producto === $value['id']
                        && (
                            $producto->cant_recibida < $value['cantidad']
                            && $producto->cant_solicitada >= $value['cantidad']
                        )
                    ) {
                        try {
                            // Actualizar el producto
                            $producto->cant_recibida = intval($value['cantidad']);
                            $producto->save();
                        } catch (\Exception $e) {
                            return <<<HTML
                            <div class="alert alert-error" x-bind="toggle=true">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                            <span >Error al actualizar el producto ID: {$producto->id_producto}. Mensaje: {$e->getMessage()}</span>
                            </div>
                            HTML;
                        }
                    }
                }
            }

            // Respuesta detallada
            return <<<HTML
            <div class="alert alert-success" x-bind="toggle=true">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
            <span >Solicitud actualizada de manera exitosa.</span>
            <script>
            setTimeout(() => {
                window.location.assign('/gestion');
            }, 1000);
            </script>
            </div>
            HTML;
        }
    }
}
