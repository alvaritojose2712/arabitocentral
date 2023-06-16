<?php

namespace App\Http\Controllers;

use App\Models\gastos;
use Illuminate\Http\Request;
use Response;


class GastosController extends Controller
{
    public function sendGastos(Request $req)
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
        $fechasMain1 = $req->fechasMain1;
        $fechasMain2 = $req->fechasMain2;
        $filtros = $req->filtros;

        $gastos = gastos::whereBetween("fecha",[$fechasMain1,$fechasMain2])
        ->orderBy("created_at","desc")
        ->get();
        
        return $gastos;
    }
}
