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
            ->where('cc.id_comprador', $comprador)
            ->whereIn('e.id', function ($query) {
                $query->select('estado')
                    ->from('SolicitudesHistorico')
                    ->whereColumn('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
                    ->orderBy('fecha', 'desc')
                    ->limit(1);
            })
            ->orderByRaw('e.id ASC, sh.fecha DESC')
            ->paginate(10);
*/

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
                'usuario',
                'prioridadSolicitud',
                'categoriaSolicitud',
                'vwArea',
                'vwCcosto',
                'ultimoEstado.estadoDetalles'
            ])
            ->orderBy('sh.estado')
            //->orderBy('solicitudes.prioridad')
            ->orderBy('sh.fecha', 'desc')
            ->select('solicitudes.*')
            ->get();

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

        $solicitud = Solicitud::with([
            'productos',
            'usuario',
            'prioridadSolicitud',
            'categoriaSolicitud',
            'vwArea',
            'vwCcosto',
            'ultimoEstado.estadoDetalles'
        ])->where('numero', $n)->first();

        //dd();

        if (!$solicitud) {
            // Manejar el caso cuando no se encuentra
            return redirect()->back()->with('error', 'Solicitud no encontrada');
        }

        $data = [
            'page' => [
                'parent' => [self::PARENT_PAGE, route(self::URL)],
                'name' => "Solicitud $n"
            ],
            'state' => $solicitud->ultimoEstado->estadoDetalles->estado,
            'solicitud' => $solicitud
        ];

        return view('dashboard.gestor.estado', $data);
    }

    public function changeState($id)
    {
        $solicitud = Solicitud::find($id);
        $comprador = Auth::user()->id;
        if (!$solicitud) {
            return response()->json(['message' => 'Not found'], 404);
        }

        try {
            //Agregar comprador a solicitud
            $solicitud->update([
                'id_comprador' => $comprador
            ]);

            //registrar estado
            $history = new SolicitudHistorico();
            $history->id_solicitud = $id;
            $history->estado = self::ESTADOS['aprobada'];
            $history->id_usuario = $comprador;
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
        } catch (\Throwable $th) {
            return <<<HTML
            <div class="alert alert-error" x-bind="toggle=true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" /><path d="M12 17l.01 0" /><path d="M12 11l0 3" /></svg>
                <span >Error: {{$th->getMessage()}}</span>
            </div>
            HTML;
        }
    }

    public function cerrarsolicitud()
    {
        DB::transaction(function () {
            // Para procedimientos sin parámetros
            DB::statement('EXEC sp_ActualizarProductosExistentes');
            DB::statement('EXEC sp_CerrarSolicitud');
            DB::statement('EXEC sp_UltimaSincronizacion');

            // Si necesitas capturar resultados
            // $result = DB::select('EXEC sp_ObtenerDatos');
        });

        return redirect()->route('gestion.home');
    }
}
