<?php

namespace App\Http\Controllers;

use App\Models\moneda;
use App\Models\sucursal;

use App\Http\Requests\StoremonedaRequest;
use App\Http\Requests\UpdatemonedaRequest;
use Illuminate\Http\Request;

use Response;


class MonedaController extends Controller
{
    public function getMonedaSucursal(Request $req)
    {
        $sucursal = sucursal::where("codigo",$req["codigo"])->first();
        if (!$sucursal) {
            return Response::json(["estado"=>false, "msj"=>"Desde central: No se encontró sucursal->".$req["sucursal"]["codigo"]]);
        }


        return moneda::where("id_sucursal",$sucursal->id)->orderBy("id","desc")->get();

        ;
    }


    public function setnewtasainsucursal(Request $req)
    {
        $tipo = $req->tipo;
        $valor = $req->valor;
        $id_sucursal = $req->id_sucursal;

        try{
            $m = moneda::updateOrCreate([
            "id_sucursal" => $id_sucursal,
            "tipo" => $tipo,
            ],[
                "valor" => $valor,
            ]);


            return Response::json(["msj"=>"Éxito al actualizar moneda ","estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error de Central: ".$e->getMessage(),"estado"=>false]);
        } 

    }
}
