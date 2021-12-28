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
            $obj = gastos::UpdateOrCreate([
                "id_local"=>$val["id"],
                "id_sucursal"=>$sucursal->id,
            ],[
                "id_sucursal" => $sucursal->id,
                "descripcion" => $val["descripcion"],
                "tipo" => $val["tipo"],
                "categoria" => $val["categoria"],
                "monto" => $val["monto"],

                "id_local"=>$val["id"],
            ]);
                
            if ($obj) {
                $arr_ok[] = $val["id"];
            }
        }

        gastos::where("id_sucursal",$sucursal->id)->whereNotIn("id_local",$arr_ok)->delete();

        return Response::json(["msj"=>"Éxito al Registrar gastos","estado"=>true]);
    }

    public function getGastos(Request $req)
    {   
        $fecha = $req->fechaGastos;
        if (!$fecha) {
            $get = gastos::where("id_sucursal",$req->id_sucursal)->orderBy("id","desc");
            // code...
        }else{
            $get = gastos::where("id_sucursal",$req->id_sucursal)->where("created_at","LIKE",$fecha."%")->orderBy("id","desc");

        }
        
        $gastos = $get->get();
        
        return $gastos;
    }
}
