<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Categoria;
use App\Models\CentroCosto;
use App\Models\Prioridad;
use App\Models\Producto;
use App\Models\Solicitud;
use App\Models\SolicitudHistorico;
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
        /*
        ESTA ES LA MANERA DE MOSTRARLO A LOS GESTIONADORES

        $items = Solicitud::join('solicitudeshistorico as sh', function ($join) {
            $join->on('solicitudes.id', '=', 'sh.id_solicitud');
        })
            ->with([
                'productos',
                'comprador',
                'prioridadSolicitud',
                'categoriaSolicitud',
                'vwArea',
                'vwCcosto',
                'ultimoEstado.estadoDetalles'
            ])
            ->orderBy('solicitudes.prioridad')
            ->orderBy('sh.estado')
            ->orderBy('sh.fecha')
            ->select('solicitudes.*')
            ->get();*/

        $items = Solicitud::join('solicitudeshistorico as sh', function ($join) {
            //$join->on('solicitudes.id', '=', 'sh.id_solicitud');

            // Primero obtenemos el último histórico de cada solicitud
            $subquery = DB::table('solicitudeshistorico')
                ->select('id_solicitud', DB::raw('MAX(fecha) as ultima_fecha'))
                ->groupBy('id_solicitud');

            $join->on('solicitudes.id', '=', 'sh.id_solicitud')
                ->joinSub($subquery, 'ultimo', function ($join) {
                    $join->on('sh.id_solicitud', '=', 'ultimo.id_solicitud')
                        ->on('sh.fecha', '=', 'ultimo.ultima_fecha');
                });
        })
            ->with([
                'productos',
                'comprador',
                'prioridadSolicitud',
                'categoriaSolicitud',
                'vwArea',
                'vwCcosto',
                'ultimoEstado.estadoDetalles'
            ])
            ->where('solicitudes.id_usuario', Auth::user()->id)
            ->orderBy('sh.estado')
            //->orderBy('solicitudes.prioridad')
            ->orderBy('sh.fecha', 'desc')
            ->select('solicitudes.*')
            ->get();



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
     * Productos
     */
    public function productos()
    {
        $data = [
            'page' => [
                'name' => 'Buscar productos en almacén'
            ]
        ];
        return view('dashboard.solicitud.productos', $data);
    }

    /**
     * Vista nueva solicitud
     */
    public function nueva(Request $request)
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
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => 'Nueva Solicitud'
            ],
            'solic_num' => $sum . '/' . date('Y'),
            'categorias' => Categoria::all(),
            'prioridades' => Prioridad::orderBy('id', 'asc')->get(),
            'areas' => Area::all(),
            'c_costo' => CentroCosto::all(),
            'productos' => json_decode($request->post('productos'), false),
        ];
        return view('dashboard.solicitud.nueva', $data);
    }

    public function mostrar($anno, $numb)
    {
        $n = implode('/', [$numb, $anno]);

        $solicitud = Solicitud::with('productos', 'ultimoEstado')->where('numero', $n)->firstOrFail();

        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => "Solicitud No. $n"
            ],
            'solicitud' => $solicitud,
            'categorias' => Categoria::all(),
            'c_selected' => $solicitud->categoria,
            'prioridades' => Prioridad::orderBy('id', 'asc')->get(),
            'p_selected' => $solicitud->prioridad,
            'areas' => Area::all(),
            'a_selected' => $solicitud->area,
            'c_costo' => CentroCosto::all(),
            'cc_selected' => $solicitud->ccosto,
            'detalles' => $solicitud->detalles,
            'productos' => $solicitud->productos
        ];
        return view('dashboard.solicitud.update', $data);
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
                'id_usuario' => Auth::user()->id,
                'categoria' => $request->post('categoria'),
                'prioridad' => $request->post('prioridad'),
                'detalles' => $request->post('detalles') ?? '',
                'area' => $request->post('area'),
                'ccosto' => $request->post('ccosto')
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
                        'cant_solicitada' => $producto['Solicitado'],
                        'almacen' => $producto['Id_Almacen']??null

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

    /**
     * Actualiza solicitud
     */
    public function updSolicitud(Request $request)
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
            $solicitud = Solicitud::with('productos')->find($request->post('id'));

            $solicitud->update([
                'categoria' => $request->post('categoria'),
                'prioridad' => $request->post('prioridad'),
                'detalles' => $request->post('detalles') ?? '',
                'area' => $request->post('area'),
                'ccosto' => $request->post('ccosto'),
            ]);

            // Obtener todos los IDs de productos que ya están asociados a esta solicitud
            $productosExistentes = $solicitud->productos()->pluck('id_producto')->toArray();

            // Obtener los IDs de productos que vienen del frontend
            $productosNuevosIds = array_column($productos, 'id_producto');

            // Encontrar los IDs que están en la base pero no en la nueva lista
            $productosAEliminar = array_diff($productosExistentes, $productosNuevosIds);

            // Eliminar los productos que no están en la nueva lista
            if (!empty($productosAEliminar)) {
                $solicitud->productos()
                    ->whereIn('id_producto', $productosAEliminar)
                    ->delete();
            }

            foreach ($productos as $producto) {

                $solicitud->productos()
                    ->where('id_producto', $producto['id_producto'])
                    ->update([
                        'cant_solicitada' => $producto['Solicitado'] ?? $producto['cant_solicitada']
                    ]);
            }

            $route = route('solicitud.home');
            return <<<HTML
            <div class="alert alert-success" x-bind="toggle=true"><svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg><span >Solicitud actualizada correctamente en la base de datos.</span><script>setTimeout(() => { window.location.assign('{$route}'); }, 1000);</script></div>
            HTML;
            return;
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
        $solicitud = new SolicitudHistorico();
        $solicitud->id_solicitud = $id;
        $solicitud->estado = 4;
        $solicitud->id_usuario = Auth::user()->id;
        $solicitud->save();


        return response()->json(['saved' => 'ok'], 200);
    }
}
