<?php

namespace App\Http\Controllers;

use App\Models\items_pedidos;
use App\Http\Requests\Storeitems_pedidosRequest;
use App\Http\Requests\Updateitems_pedidosRequest;
use App\Models\pedidos;
use Illuminate\Http\Request;

use Response;

class ItemsPedidosController extends Controller
{
   function sendItemsPedidosChecked(Request $req) {
        $items = $req->items;

        if (count($items)) {
            $id_pedido = $items[0]["id_pedido"];
            $pedido = pedidos::find($id_pedido);

            if ($pedido) {
                $estatus_actual = $pedido->estado;
                
                if ($estatus_actual==1 || $estatus_actual==3) {
                    $pedido->estado = 3;
                    $pedido->save();
                    foreach ($items as $i => $item) {
                        $items_pedidos = items_pedidos::find($item["id"]);
                        $items_pedidos->ct_real = isset($item["ct_real"])?$item["ct_real"]:null;
                        $items_pedidos->barras_real = isset($item["barras_real"])?$item["barras_real"]:null;
                        $items_pedidos->alterno_real = isset($item["alterno_real"])?$item["alterno_real"]:null;
                        $items_pedidos->save();
                    }

                    return Response::json(["msj"=>"En revisión 3", "estado" => false, "proceso"=>"enrevision"]);
                }else if($estatus_actual==4){
                    
                    $items_new = items_pedidos::with(["producto"=>function($q){
                        $q->with(["categoria","proveedor"]);
                    }])
                    ->where("id_pedido",$id_pedido)
                    ->get();
                    return Response::json(["msj"=>"Revisado 4", "estado" => true, "items_new"=>$items_new]) ;
                }
                return Response::json(["msj"=>"Error: ESTADO: ".$estatus_actual, "estado" => false]) ;
            }
            return Response::json(["msj"=>"No se encontró pedido", "estado" => false]) ;
            
        }
        
        return Response::json(["msj"=>"Error: Sin items", "estado" => false]) ;
   }
}
