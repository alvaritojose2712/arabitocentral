<?php

namespace App\Http\Controllers;

use App\Models\fallas;
use App\Models\sucursal;
use App\Http\Requests\StorefallasRequest;
use App\Http\Requests\UpdatefallasRequest;
use Illuminate\Http\Request;
use Response;


class FallasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setFallas(Request $req)
    {

        $sucursal = sucursal::where("codigo",$req->sucursal_code)->first();

        if (!$sucursal) {
            return Response::json([
                "msj"=>"No se encontró sucursal",
                "estado"=>false
            ]);
        }

        $fallas = $req->fallas;
        // return Response::json(["msj"=>$fallas,"estado"=>true]);

        $arr_ok = [];
        foreach ($fallas as $val) {
            // code...
            $uoc = fallas::UpdateOrCreate([
                "id_local"=>$val["id"],
                "id_sucursal"=>$sucursal->id,
            ],[

                "id_local"=>$val["id"],
                "id_producto"=>$val["id_producto"],
                "id_sucursal"=>$sucursal->id,
                "cantidad"=>$val["cantidad"],
            ]);
            if ($uoc) {
                $arr_ok[] = $val["id"];
            }else{
                return Response::json([
                    "msj"=>"No se encontró producto",
                    "estado"=>false
                ]);       
            }
        }
        fallas::where("id_sucursal",$sucursal->id)->whereNotIn("id_local",$arr_ok)->delete();
        return Response::json(["msj"=>"Éxito al registrar fallas","estado"=>true]);
    }

    public function getFallas(Request $req)
    {
        return fallas::with("producto")->where("id_sucursal",$req->id_sucursal)->get();
    }


}
