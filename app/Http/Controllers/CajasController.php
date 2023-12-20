<?php

namespace App\Http\Controllers;

use App\Models\cajas;
use App\Http\Requests\StorecajasRequest;
use App\Http\Requests\UpdatecajasRequest;
use App\Models\catcajas;
use Illuminate\Http\Request;
use Response;
class CajasController extends Controller
{
    function setEfecFromSucursalToCentral($movs, $id_sucursal) {

        try {
            $count_movs = count($movs);
            $counter =0;
            $last = 0;

            foreach ($movs as $e) {
                if ($last<$e["id"]) {
                    $last=$e["id"];
                }

                $catnombre = $e["cat"]["nombre"];
                $cattipo = $e["cat"]["tipo"];
                $catindice = $e["cat"]["indice"];
                $checkcatcajas = catcajas::where("nombre",$catnombre)->where("tipo",$cattipo)->first();
                if ($checkcatcajas) {
                    $setcategoria = $checkcatcajas->id;
                }else{
                    $newcat = catcajas::updateOrCreate([
                        "nombre" => $catnombre,
                        "tipo" => $cattipo,
                    ],[
                        "indice" => $catindice,
                        "nombre" => $catnombre,
                        "tipo" => $cattipo,

                    ]);
                    
                    $setcategoria = $newcat->id; 

                }

                if (strpos($catnombre,"NOMINA")) {
                    $split = explode("=",$e["concepto"]);
                    if (isset($split[1])) {
                        $ci = $split[1];
                        $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:$e["montopeso"]);
                        (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $e["id"]);
                    }

                }
                $arr_insert = [
                    "montoeuro" => $e["montoeuro"],
                    "eurobalance" => $e["eurobalance"],

                    "responsable" => $e["responsable"],
                    "asignar" => $e["asignar"],
                    "concepto" => $e["concepto"],
                    "categoria" => $setcategoria,
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
            return [
                "msj" => "OK CAJAS ".$counter . " / ".$count_movs,
                "last" => $last
            ];
        } catch (\Exception $e) {
            return "ERROR EN setEfecFromSucursalToCentral ".$e->getMessage()." ".$e->getLine();
        }
    }
}
