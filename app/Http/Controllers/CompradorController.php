<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompradorController extends Controller
{
    public function dashboard(Request $request)
    {

        $items = Solicitud::with(['usuario', 'historico.estado', 'productos'])
        ->join(
            'SolicitudesHistorico as h',
            'Solicitudes.id',
            '=',
            'h.id_solicitud'
        )
        ->where('h.estado',1)
        ->whereNotExists(function($query){
            $query->select(DB::raw(1))
              ->from('SolicitudesHistorico')
              ->whereRaw('CAST(SolicitudesHistorico.id_solicitud AS BIGINT) = Solicitudes.id')
              //->where('SolicitudesHistorico.id_solicitud', 'Solicitudes.id')
              ->where('SolicitudesHistorico.estado', '<>', 1);
        })
        ->get();
            /*Solicitud::with(['usuario', 'historico.estado', 'productos'])
            ->whereHas('historico', function ($query) {
                $query->where('estado', 1)
                    ->orderBy('fecha', 'desc')
                    ->first();
            })
            ->get()*/;

            //dd($items);

        // Reemplaza con la fecha actual
        $lastSessionDate = session('logged.last') ?? Carbon::now();


        // Ultimos Estados de solicitud
        $pendiente = SolicitudHistorico::where([
            ['estado', '=', 1],
            ['fecha ', '>=', $lastSessionDate]
        ])->count();
        $completado = SolicitudHistorico::where([
            ['estado', '=', 3],
            ['fecha ', '>=', $lastSessionDate]
        ])->count();
        $eliminado = SolicitudHistorico::where([
            ['estado', '=', 2],
            ['fecha ', '>=', $lastSessionDate]
        ])->count();
        $proceso = SolicitudHistorico::where([
            ['estado', '=', 4]
        ])->count();


        $data = [
            'page' => [
                'title' => 'pannelComprador',
                'name' => 'Panel de Control'
            ],
            'estado' => [
                'pendiente' => $pendiente,
                'completado' => $completado,
                'eliminado' => $eliminado,
                'proceso' => $proceso,
            ],
            'items' => $items,
        ];
        return view('dashboard.comprador.home', $data);
    }
}
