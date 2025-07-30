<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompradorController extends Controller
{
    public function dashboard(Request $request)
    {

        $items = Solicitud::with(['usuario', 'historico.estado', 'productos'])
            ->join(
                'SolicitudesHistorico',
                'SolicitudesHistorico.id_solicitud',
                '=',
                'Solicitudes.id'
            )
            ->join(
                'Usuarios',
                'Usuarios.id',
                '=',
                'Solicitudes.id_usuario'
            )->join(
                'Estados',
                'Estados.id',
                '=',
                'SolicitudesHistorico.estado'
            )->select(
                'Solicitudes.*',
                /*'Estados.estado',
                'Usuarios.nombre as hecho_por',
                'Usuarios.cargo',
                'Estados.id as estado_id'*/
            )->where('Estados.id', 1)
            ->get();

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
