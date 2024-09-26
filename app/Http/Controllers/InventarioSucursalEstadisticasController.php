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

    function removeDuplicatesItemsEstadisticas() {

        $du = inventario_sucursal_estadisticas::selectRaw("id_sucursal, id_itempedido_insucursal, COUNT(*) as count")->groupByRaw("id_sucursal, id_itempedido_insucursal")->havingRaw("COUNT(*) > 1")->get();

        foreach ($du as $key => $val) {
            $id_itempedido_insucursal = $val["id_itempedido_insucursal"]; 
            $id_sucursal = $val["id_sucursal"]; 
            $count = $val["count"]-1;

            inventario_sucursal_estadisticas::where("id_itempedido_insucursal",$id_itempedido_insucursal)->where("id_sucursal",$id_sucursal)->limit($count)->delete();

            echo "$id_sucursal __ $id_itempedido_insucursal ____ $count veces <br>";
        }
    }


    
    function sendestadisticasVenta($movs,$id_sucursal) {
        
        $count_movs = count($movs);
        $counter = 0;
        $today = (new NominaController)->today();

        if ($count_movs) {
            $last=0;
            foreach ($movs as $e) {
                if ($last<$e["id"]) {
                    $last=$e["id"];
                }
                $cc = inventario_sucursal_estadisticas::updateOrCreate([
                    "id_sucursal" => $id_sucursal,
                    "id_itempedido_insucursal" => $e["id"],
                ],[
                    "id_sucursal" => $id_sucursal,
                    "id_itempedido_insucursal" => $e["id"],

                    "cantidad" => $e["cantidad"],
                    "fecha" => substr($e["created_at"],0,10),
                    "id_pedido_insucursal" => $e["id_pedido"],
                    "id_producto_insucursal" => $e["id_producto"],
                ]);

                if ($cc) {
                    $counter++;
                }
            }
            return [
                "msj" => "OK ESTADISTICAS ".$counter." / ".$count_movs,
                "last" => $last
            ];
            
        }else{
            return [
                "msj" => "OK ESTADISTICAS ".$counter . " / ".$count_movs,
                "last" => 0
            ];
        }
    }


    function delduplicateItemsEstadisticas() {
        $du = inventario_sucursal_estadisticas::selectRaw("id_sucursal, id_itempedido_insucursal, COUNT(*) as count")->groupByRaw("id_sucursal, id_itempedido_insucursal")->havingRaw("COUNT(*) > 1")->get();

        foreach ($du as $key => $val) {
            $id_itempedido_insucursal = $val["id_itempedido_insucursal"]; 
            $id_sucursal = $val["id_sucursal"]; 
            $count = $val["count"]-1;

            inventario_sucursal_estadisticas::where("id_itempedido_insucursal",$id_itempedido_insucursal)->where("id_sucursal",$id_sucursal)->limit($count)->delete();

            echo "$id_sucursal __ $id_itempedido_insucursal ____ $count veces <br>";
        }
    }

    /* foreach ($split as $i => $e) {
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
    } */
}
