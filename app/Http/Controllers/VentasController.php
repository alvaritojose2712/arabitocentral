<?php

namespace App\Http\Controllers;

use App\Models\ventas;
use App\Models\sucursal;
use App\Http\Requests\StoreventasRequest;
use App\Http\Requests\UpdateventasRequest;

use Illuminate\Http\Request;
use Response;


class VentasController extends Controller
{
    public function getVentas(Request $req)
    {
        $fecha = $req->selectfechaventa;

        if (!$fecha) {
            return ventas::where("id_sucursal",$req->id_sucursal)->orderBy("fecha","desc")->get();
            // code...
        }
        return ventas::where("id_sucursal",$req->id_sucursal)->where("fecha","LIKE",$fecha."%")->orderBy("fecha","desc")->get();

    }

    public function setVentas(Request $req)
    {
        

        $sucursal = sucursal::where("codigo",$req->sucursal_code)->first();

        if (!$sucursal) {
            return Response::json([
                "msj"=>"No se encontró sucursal",
                "estado"=>false
            ]);
        }

        $ventas = $req->ventas;
        // return Response::json(["msj"=>$ventas,"estado"=>true]);

        $arr_ok = [];
            // code...
        $uoc = ventas::UpdateOrCreate([
            "fecha"=>$ventas["fecha"],
        ],[
            "debito" => $ventas["debito"],
            "efectivo" => $ventas["efectivo"],
            "transferencia" => $ventas["transferencia"],
            "tasa" => $ventas["tasa"],
            "fecha" => $ventas["fecha"],
            "num_ventas" => $ventas["num_ventas"],
            "id_sucursal"=>$sucursal->id,
        ]);
        if ($uoc) {
            $arr_ok[] = $ventas["fecha"];
        }
        return Response::json(["msj"=>"Éxito","estado"=>true,"ids_ok"=>$arr_ok]);
    }
}
