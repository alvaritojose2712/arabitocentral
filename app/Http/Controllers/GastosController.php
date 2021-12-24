<?php

namespace App\Http\Controllers;

use App\Models\gastos;
use App\Models\sucursal;
use App\Http\Requests\StoregastosRequest;
use App\Http\Requests\UpdategastosRequest;
use Illuminate\Http\Request;
use Response;


class GastosController extends Controller
{
    public function setGastos(Request $req)
    {
        $sucursal = sucursal::where("codigo",$req->sucursal_code)->first();

        if (!$sucursal) {
            return Response::json([
                "msj"=>"No se encontró sucursal",
                "estado"=>false
            ]);
        }

        $arr_ok = [];
        $gastos = $req->movimientos_caja;
        foreach ($gastos as $val) {
            // code...
            $obj = new gastos;

            $obj->id_sucursal = $sucursal->id;
            $obj->descripcion = $val["descripcion"];
            $obj->tipo = $val["tipo"];
            $obj->categoria = $val["categoria"];
            $obj->monto = $val["monto"];
            $obj->save();
                
            if ($obj) {
                $arr_ok[] = $val["id"];
            }
        }
        return Response::json(["msj"=>"Éxito","estado"=>true,"ids_ok"=>$arr_ok]);
    }

    public function getGastos(Request $req)
    {
        return gastos::where("id_sucursal",$req->id_sucursal)->get();
    }
}
