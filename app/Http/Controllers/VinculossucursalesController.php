<?php

namespace App\Http\Controllers;

use App\Models\vinculossucursales;
use App\Models\inventario_sucursal;
use App\Models\items_pedidos;
use App\Models\pedidos;

use App\Http\Requests\StorevinculossucursalesRequest;
use App\Http\Requests\UpdatevinculossucursalesRequest;
use Illuminate\Http\Request;
use Response;

class VinculossucursalesController extends Controller
{
    function sendVinculoCentralToSucursal(Request $req) {
        $idinsucursal = $req->idinsucursal;
        $id_sucursal = $req->id_sucursal;
        $id_producto_central = $req->id_producto_central;

        $last_id = vinculossucursales::orderBy("id","desc")->first();
        $v = vinculossucursales::updateOrCreate([
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC

        ],[
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC
            
            "idinsucursal" => ($last_id?$last_id->id:0)+1, // INSUCURSAl, SOLO REF
        ]);
        if ($v) {
            return ["estado"=>1,"msj"=>"Éxito al Vincular"];
        }
    }

    function delVinculoSucursal(Request $req) {
        $id_vinculo = $req->id_vinculo;
        if (vinculossucursales::find($id_vinculo)->delete()) {
            return ["estado"=>true,"msj"=>"Éxito"];
        }
    }


    function delvinculosduplicate() {

        $du = vinculossucursales::selectRaw("id_sucursal,id_sucursal_fore,id_producto_local, count(*) as count")->groupByRaw("id_sucursal,id_sucursal_fore,id_producto_local")->havingRaw("COUNT(*) > 1")->get();

        foreach ($du as $key => $val) {
            $id_sucursal = $val["id_sucursal"]; 
            $id_sucursal_fore = $val["id_sucursal_fore"]; 
            $id_producto_local = $val["id_producto_local"]; 
            $count = $val["count"]-1;

            vinculossucursales::where("id_sucursal",$id_sucursal)
            ->where("id_sucursal_fore",$id_sucursal_fore)
            ->where("id_producto_local",$id_producto_local)
            ->orderBy("created_at","asc")
            ->limit($count)
            ->delete();

            echo "$id_sucursal __ $id_sucursal_fore __ $id_producto_local ____ $count veces <br>";
        }
    }


    function autovinculartodo() {
        $allmaestro = inventario_sucursal::where("id_sucursal",13)->get();

        foreach ($allmaestro as $i => $e) {
            if ($e->codigo_barras) {
                $match = inventario_sucursal::where("codigo_barras",$e->codigo_barras)->where("id_sucursal","<>",13)->first();

                if ($match) {
                    vinculossucursales::updateOrCreate([
                        "id_sucursal" => 13, //CENTRAL
                        "id_sucursal_fore" => $match->id_sucursal, //SUC
                        "id_producto_local" => $e->id, //PROD CENTRAL
                    ],[
                        "idinsucursal_fore" => $match->idinsucursal, //PROD SUC
                        "idinsucursal" => null, // INSUCURSAl, SOLO REF
                    ]);
                }
            }
        }
    }

    function removeVinculoCentral(Request $req) {
        
        $id = $req->id;

        $item = items_pedidos::find($id);

        if ($item) {
           $id_producto = $item->id_producto;
           $pedido = pedidos::find($item->id_pedido);

           $delvin = vinculossucursales::where("id_sucursal",13)
           ->where("id_producto_local",$id_producto)
           ->where("id_producto_local",$id_producto)
           ->where("id_sucursal_fore",$pedido->id_destino)
           ->delete();
            if ($delvin) {
                return ["estado"=>true, "id_pedido"=>$item->id_pedido, "id_item"=>$id];
            }
            return ["estado"=>false];
        }
    }
}
