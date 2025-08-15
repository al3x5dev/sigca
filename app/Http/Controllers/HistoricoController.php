<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudHistorico;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    private const STATE = [
        'approve' => 2,
        'success' => 3,
        'cancel' => 4,
    ];
    public function state(Request $request, $id)
    {
        $data = match ($request->input('state')) {
            self::STATE['approve'] => $this->approve($id/*, $state*/),
            self::STATE['success'] => $this->success($id/*, $state*/),
            self::STATE['cancel'] => $this->cancel($id/*, $state*/),
        };

        return response()->json(['message' => $data]);
    }

    public function approve(int $id)
    {
        $history = SolicitudHistorico::where('id_solicitud', $id)
            ->where('estado', self::STATE['approve'])
            ->first();

        if ($history) {
            return "Registro existente. Ya existe la solicitud $id, para el estado 2";
        }

        $newRegistro = new SolicitudHistorico();
        $newRegistro->id_solicitud = $id;
        $newRegistro->estado = self::STATE[__FUNCTION__];
        $newRegistro->save();

        $solicitud= Solicitud::find($id);
        $solicitud->id_comprador=session('logged.id');
        $solicitud->save();

        return "Registro actualizado. La solicitud con ID $id, paso a estar en proceso.";
    }

    public function success(int $id)
    {
        $newRegistro = new SolicitudHistorico();
        $newRegistro->id_solicitud = $id;
        $newRegistro->estado = self::STATE[__FUNCTION__];

        $newRegistro->save();

        return '';
    }

    public function cancel(int $id)
    {
        $newRegistro = new SolicitudHistorico();
        $newRegistro->id_solicitud = $id;
        $newRegistro->estado = self::STATE[__FUNCTION__];

        $newRegistro->save();

        return '';
    }
}
