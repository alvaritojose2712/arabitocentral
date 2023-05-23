<?php

namespace App\Http\Controllers;

use App\Models\gastos;
use Illuminate\Http\Request;
use Response;


class GastosController extends Controller
{
    public function setGastos(Request $req)
    {
        try {
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_origen = $id_ruta["id_origen"];
    
            $gastos = $req->gastos;
            foreach ($gastos as $val) {
                $obj = gastos::updateOrCreate([
                    "id_local"=>$val["id"],
                    "id_sucursal"=>$id_origen,
                ],[
                    "descripcion" => $val["descripcion"],
                    "tipo" => 1,
                    "categoria" => $val["categoria"],
                    "monto" => $val["monto"],
                ]);
            }
    
            return "Central: Éxito al Registrar gastos";
        } catch (\Exception $e) {
            return "Error en Central: ".$e->getMessage();
        }
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
