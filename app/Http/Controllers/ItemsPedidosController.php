<?php

namespace App\Http\Controllers;

use App\Models\items_pedidos;
use App\Http\Requests\Storeitems_pedidosRequest;
use App\Http\Requests\Updateitems_pedidosRequest;
use App\Models\pedidos;
use Illuminate\Http\Request;

use Response;
use DB;

class ItemsPedidosController extends Controller
{
   function sendItemsPedidosChecked(Request $req) {
        $items = $req->items;

        DB::beginTransaction();
        try {
            
            if (count($items)) {
                $id_pedido = $items[0]["id_pedido"];
                $pedido = pedidos::find($id_pedido);
    
                if ($pedido) {
                    $estatus_actual = $pedido->estado;
                    
                    if ($estatus_actual==1) {
                        foreach ($items as $i => $item) {
                            $items_pedidos = items_pedidos::find($item["id"]);
                            $items_pedidos->ct_real = @$item["ct_real"];
                            $items_pedidos->barras_real = @$item["barras_real"];

                            if (!$item["barras_real"]&&!$item["producto"]["codigo_barras"]) {
                                return Response::json(["msj"=>"Falta COD. BARRAS ".@$item["producto"]["codigo_proveedor"], "estado" => false, "proceso"=>"enrevision"]);
                            }
                            $items_pedidos->alterno_real = @$item["alterno_real"];
                            
                            $items_pedidos->descripcion_real = @$item["descripcion_real"];
                            $items_pedidos->vinculo_real = @$item["vinculo_real"];
                            $items_pedidos->save();
                        }
                        $pedido->estado = 3;
                        $pedido->save();

                        DB::commit();
                        return Response::json(["msj"=>"En revisión 3", "estado" => false, "proceso"=>"enrevision"]);
                    }else if($estatus_actual==3){

                        DB::commit();
                        return Response::json(["msj"=>"Error: No se pueden HACER CAMBIOS. Aún en revisión 3", "estado" => false, "proceso"=>"enrevision"]);
                    }else if($estatus_actual==4){
                        
                        $items_new = items_pedidos::with(["producto"=>function($q){
                            $q->with(["categoria","proveedor"]);
                        }])
                        ->where("id_pedido",$id_pedido)
                        ->get();
                        DB::commit();
                        return Response::json(["msj"=>"Revisado 4", "estado" => true, "items_new"=>$items_new]) ;
                    }
                    return Response::json(["msj"=>"Error: ESTADO: ".$estatus_actual, "estado" => false]) ;
                }
                return Response::json(["msj"=>"No se encontró pedido", "estado" => false]) ;
                
            }
        }catch (\Exception $e) {
                    
            DB::rollback();
            return Response::json(["msj"=>"Error: Sin items", "estado" => false]) ;
        }

        
        
   }
}
