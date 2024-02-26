<?php

namespace App\Http\Controllers;

use App\Models\nomina;
use App\Models\cajas;
use Illuminate\Http\Request;

use App\Models\nominapagos;
use App\Http\Requests\StorenominapagosRequest;
use App\Http\Requests\UpdatenominapagosRequest;
use Response;
class NominapagosController extends Controller
{
    function configPagos(){
        $cajas = cajas::with(["cat","sucursal"])->orderBy("fecha","asc")->get();
        $num_nulls = 0;
        foreach ($cajas as $key => $e) {
            if ($e["cat"]) {
                $catnombre = $e["cat"]["nombre"];
                $id_sucursal = $e["id_sucursal"];
                if (strpos($catnombre,"NOMINA")) {
                    $split = explode("=",$e["concepto"]);
                    if (isset($split[1])) {
                        $ci = $split[1];
                        $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:$e["montopeso"]);
                        (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                    }
                    
                }
            }else{
                $num_nulls++;
            }
        }
        return $num_nulls." IGNORADOS DE ".$cajas->count();
    }
    function setPagoNomina($ci, $monto, $id_sucursal, $idinsucursal,$fecha="") {

        $id_nomina = nomina::where("nominacedula",$ci)->first("id");
        if ($id_nomina) {
            $id_nomina = $id_nomina->id;

            return nominapagos::updateOrCreate([
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
            ],[
                "monto" => $monto,
                "descripcion" => "PAGO NOMINA",
                "id_nomina" => $id_nomina,
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
                "created_at" => $fecha?$fecha:date("Y-m-d H:i:s"),
            ]);
        }
    }
}
