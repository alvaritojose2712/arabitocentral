<?php

namespace App\Http\Controllers;

use App\Models\movsinventario;
use App\Http\Requests\StoremovsinventarioRequest;
use App\Http\Requests\UpdatemovsinventarioRequest;

class MovsinventarioController extends Controller
{
    function sendlasmovs_movs($movs, $id_sucursal) {

            /*  $bs = (new CierresController)->getTasa()["bs"];
             $cop = (new CierresController)->getTasa()["cop"]; */
         
             $count_movs = count($movs);
             $counter =0;
             $last = 0;
             if ($count_movs) {
                 //return $movs;
                 foreach ($movs as $e) {
                     if ($last<$e["id"]) {
                         $last=$e["id"];
                     }
     
                     
 
                     $cc =  movsinventario::updateOrCreate([
                         "id_sucursal" => $id_sucursal,
                         "idinsucursal" => $e["id"],
                     ],[
                        "idinsucursal" => $e["id"],
                        "id_producto" => $e["id_producto"],
                        "id_pedido" => $e["id_pedido"],
                        "id_usuario" => $e["id_usuario"],
                        "cantidad" => $e["cantidad"],
                        "cantidadafter" => $e["cantidadafter"],
                        "origen" => $e["origen"],
                        "id_sucursal" => $id_sucursal,
                     ]);
     
                     if ($cc) {
                         $counter++;
                     }
                 }
                 return [
                     "msj" => "OK MOVS ".$counter . " / ".$count_movs,
                     "last" => $last
                 ];
             }else{
                 return [
                     "msj" => "ERROR MOVS ".$counter . " / ".$count_movs,
                     "last" => 0
                 ];
             }
         
     }
}
