<?php

namespace App\Http\Controllers;

use App\Models\cierres;
use Illuminate\Http\Request;


class CierresController extends Controller
{
   public function setCierreFromSucursalToCentral(Request $req)
   {
        try {
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_origen = $id_ruta["id_origen"];
            
            $cierre = $req->cierre;

            
            $cierresobj = cierres::updateOrCreate([
                "fecha" => $cierre["fecha"],
            ],[
                "debito" => $cierre["debito"],
                "efectivo" => $cierre["efectivo"],
                "transferencia" => $cierre["transferencia"],
                "caja_biopago" => $cierre["caja_biopago"],
                "dejar_dolar" => $cierre["dejar_dolar"],
                "dejar_peso" => $cierre["dejar_peso"],
                "dejar_bss" => $cierre["dejar_bss"],
                "efectivo_guardado" => $cierre["efectivo_guardado"],
                "efectivo_guardado_cop" => $cierre["efectivo_guardado_cop"],
                "efectivo_guardado_bs" => $cierre["efectivo_guardado_bs"],
                "tasa" => $cierre["tasa"],
                "nota" => $cierre["nota"],
                "id_usuario" => $cierre["id_usuario"],
                "numventas" => $cierre["numventas"],
                "precio" => $cierre["precio"],
                "precio_base" => $cierre["precio_base"],
                "ganancia" => $cierre["ganancia"],
                "porcentaje" => $cierre["porcentaje"],
                "desc_total" => $cierre["desc_total"],
                "efectivo_actual" => $cierre["efectivo_actual"],
                "efectivo_actual_cop" => $cierre["efectivo_actual_cop"],
                "efectivo_actual_bs" => $cierre["efectivo_actual_bs"],
                "puntodeventa_actual_bs" => $cierre["puntodeventa_actual_bs"],
                "id_sucursal" => $id_origen,
            ]);
            
            if ($cierresobj->save()) {
                return "Éxito al registrar cierre en Central";
            }        
        } catch (\Exception $e) {
            return "Error: ".$e->getMessage();
        }
   }
}
