<?php

namespace App\Http\Controllers;

use App\Models\fallas;
use App\Models\sucursal;
use App\Models\inventario;

use App\Http\Requests\StorefallasRequest;
use App\Http\Requests\UpdatefallasRequest;
use Illuminate\Http\Request;
use Response;


class FallasController extends Controller
{

    public function sendFallas($fallas,$id_origen)
    {
        $arr_ok = [];
        $num = 0;
        $last = 0;
        foreach ($fallas as $e) {
            if ($last<$e["id"]) {
                $last=$e["id"];
            }
            $uoc = fallas::updateOrCreate([
                "idinsucursal" => $e["id"],
                "id_sucursal" => $id_origen,
            ],[
                "idinsucursal" => $e["id"],
                "id_sucursal" => $id_origen,

                "id_producto" => $e["producto"]["id"],
                "cantidad" => $e["producto"]["cantidad"],
                "stockmin" => $e["producto"]["stockmin"],
                "stockmax" => $e["producto"]["stockmax"],
            ]);
            if ($uoc) {
                $arr_ok[] = $e["id"];
                $num++;
            }
        }
        return [
            "msj" => "OK FALLAS ".$num." / ".count($fallas),
            "last" => $last
        ];
    }

    public function getFallas(Request $req)
    {
        return fallas::with("producto")->where("id_sucursal",$req->id_sucursal)->get();
    }


}
