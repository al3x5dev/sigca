<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('p');

        if (!empty($query)) {
            // Realiza la consulta a la base de datos
            $products = DB::connection('une_2316a_int')
                ->table('vw_SIGCA_ProductosExistencia')
                ->select('Id_Producto', 'Desc_Producto', 'Existencia_Actual','UM_Almacen')
                ->where('Desc_Producto', 'LIKE', "%{$query}%")
                /*->take(15)*/
                ->get();
        } else {
            $products=[];
        }


        // Devuelve la respuesta JSON
        return response()->json($products);
    }
}
