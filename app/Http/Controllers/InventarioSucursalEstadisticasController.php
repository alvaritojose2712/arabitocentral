<?php

namespace App\Http\Controllers;

set_time_limit(9000000);
ini_set('memory_limit', '4095M');


use App\Models\inventario_sucursal;
use App\Models\inventario_sucursal_estadisticas;
use App\Http\Requests\Storeinventario_sucursal_estadisticasRequest;
use App\Http\Requests\Updateinventario_sucursal_estadisticasRequest;
use DB;

class InventarioSucursalEstadisticasController extends Controller
{
    function sendestadisticasVenta($movs,$id_sucursal) {
        $count_movs = count($movs);
            $counter =0;
            $today = (new NominaController)->today();

            if ($count_movs) {
                $split = array_chunk($movs,500);

                foreach ($split as $i => $e) {
                    $tempArr = [];

                    foreach ($e as $key => $item) {
                        array_push($tempArr,[
                            "id_itempedido_insucursal" => $item["id"],
                            "id_pedido_insucursal" => $item["id_pedido"],
                            "id_producto_insucursal" => $item["id_producto"],
                            
                            "id_sucursal" => $id_sucursal,
                            "cantidad" => $item["cantidad"],
                            "fecha" => substr($item["created_at"],0,10),
                            "created_at" => $today,
                        ]);
                    }

                    DB::table("inventario_sucursal_estadisticas")->insert($tempArr);
                }
                return [
                    "msj" => "OK ESTADISTICAS ".$count_movs,
                    "last" => $movs[0]["id"]
                ];

               /*  foreach ($movs as $e) {
                    if ($last<$e["id"]) {
                        $last=$e["id"];
                    }
                    //$get = inventario_sucursal::where("id_sucursal",$id_sucursal)->where("idinsucursal",$e["id_producto"])->first(["id"]);
                    
                    $cc = inventario_sucursal_estadisticas::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $e["id"],
                    ],[
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $e["id"],
                        "cantidad" => $e["cantidad"],
                        "fecha" => substr($e["created_at"],0,10),
                    ]);

                    if ($cc) {
                        $counter++;
                    }
                    /* if (!$get) {
                        $i = inventario_sucursal::updateOrCreate([
                            "id" => null
                        ],[
                            "id_sucursal" => $id_sucursal,
                            "idinsucursal" => $e["producto"]["id"],
                            "codigo_barras" => $e["producto"]["codigo_barras"],
                            "codigo_proveedor" => $e["producto"]["codigo_proveedor"],
                            "descripcion" => $e["producto"]["descripcion"],
                            "unidad" => $e["producto"]["unidad"],
                            "id_categoria" => $e["producto"]["id_categoria"],
                            "id_catgeneral" => $e["producto"]["id_categoria"],
                            "iva" => $e["producto"]["iva"],
                            "precio" => $e["producto"]["precio"],
                            "precio_base" => $e["producto"]["precio_base"],
                            "cantidad" => $e["producto"]["cantidad"],
                        ]); 
                        $id_inventario_sucursal = $i->id; 

                    }*/
                //} */
                
            }else{
                return [
                    "msj" => "OK ESTADISTICAS ".$counter . " / ".$count_movs,
                    "last" => 0
                ];
            }
    }
}
