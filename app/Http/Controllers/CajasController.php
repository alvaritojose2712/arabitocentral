<?php

namespace App\Http\Controllers;

use App\Models\cajas;
use App\Http\Requests\StorecajasRequest;
use App\Http\Requests\UpdatecajasRequest;
use Illuminate\Http\Request;
use Response;
class CajasController extends Controller
{
    

   
    public function setEfecFromSucursalToCentral(Request $req) {

        try {
            
            $codigo_origen =  $req->codigo_origen;
            

            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];

            $count_movs = count($req->movs);

            $counter =0;
            foreach ($req->movs as $key => $e) {
                $arr_insert = [
                    "concepto" => $e["concepto"],
                    "categoria" => $e["categoria"],
                    "montodolar" => $e["montodolar"],
                    "montopeso" => $e["montopeso"],
                    "montobs" => $e["montobs"],
                    "dolarbalance" => $e["dolarbalance"],
                    "pesobalance" => $e["pesobalance"],
                    "bsbalance" => $e["bsbalance"],
                    "fecha" => $e["fecha"],
                    "tipo" => $e["tipo"],
                    "id_sucursal" => $id_sucursal,
                    "idinsucursal" => $e["id"],
                    ] ; 
                    $cc =  cajas::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $e["id"],
                        
                    ],$arr_insert);

                    if ($cc) {
                        $counter++;
                    }
            }
    
            
            $msj = $counter . " de ".$count_movs;
            return $msj;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
