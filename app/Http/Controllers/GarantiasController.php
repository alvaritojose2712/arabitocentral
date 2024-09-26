<?php

namespace App\Http\Controllers;

use App\Models\garantias;
use App\Models\inventario_sucursal;
use App\Models\inventario;
use App\Http\Requests\StoregarantiasRequest;
use App\Http\Requests\UpdategarantiasRequest;

use Illuminate\Http\Request;
use Response;

class GarantiasController extends Controller
{
   function sendGarantias($garantias,$id_origen) {
      $arr_ok = [];
      $num = 0;
      $last = 0;
      foreach ($garantias as $e) {
            $id_producto = $e["producto"]["id"];
            $idinsucursal = $e["id"];
            $cantidad = $e["cantidad"];

            if ($last<$idinsucursal) {
                  $last=$idinsucursal;
            }

            $uoc = garantias::updateOrCreate([
                  "id_sucursal" => $id_origen,
                  "idinsucursal" => $idinsucursal,
            ],[
                  "id_sucursal" => $id_origen,
                  "idinsucursal" => $idinsucursal,
                  "id_producto" => $id_producto,
                  "cantidad" =>  $cantidad,
                  "id_cliente" => 1,
                  
                  "motivo" =>  isset($e["motivo"])?$e["motivo"]:null,
                  "cantidad_salida"=>isset($e["cantidad_salida"])?$e["cantidad_salida"]:null,
                  "motivo_salida"=>isset($e["motivo_salida"])?$e["motivo_salida"]:null,
                  "ci_cajero"=>isset($e["ci_cajero"])?$e["ci_cajero"]:null,
                  "ci_autorizo"=>isset($e["ci_autorizo"])?$e["ci_autorizo"]:null,
                  "dias_desdecompra"=>isset($e["dias_desdecompra"])?$e["dias_desdecompra"]:null,
                  "ci_cliente"=>isset($e["ci_cliente"])?$e["ci_cliente"]:null,
                  "telefono_cliente"=>isset($e["telefono_cliente"])?$e["telefono_cliente"]:null,
                  "nombre_cliente"=>isset($e["nombre_cliente"])?$e["nombre_cliente"]:null,
                  "nombre_cajero"=>isset($e["nombre_cajero"])?$e["nombre_cajero"]:null,
                  "nombre_autorizo"=>isset($e["nombre_autorizo"])?$e["nombre_autorizo"]:null,
                  "trajo_factura"=>isset($e["trajo_factura"])?$e["trajo_factura"]:null,
                  "motivonotrajofact"=>isset($e["motivonotrajofact"])?$e["motivonotrajofact"]:null,
            ]);
            if ($uoc) {
                  $arr_ok[] = $e["id"];
                  $num++;
            }
      }

      
      
      
      
      
      
      
      
      
      
      
      

      return [
            "msj" =>"OK GARANTIAS ".$num." / ".count($garantias),
            "last" => $last
      ];
   }

   function getGarantias(Request $req) {
      $garantiaq = $req->garantiaq;
      $garantiaqsucursal = $req->garantiaqsucursal;

      return garantias::with("sucursal")
      ->when($garantiaqsucursal,function($q) {
            $q->where("id_sucursal",$garantiaqsucursal);
      })
      ->when($garantiaq,function($q) {
            $q->where("motivo","LIKE","%$garantiaq%");
      })
      ->get()
      ->map(function($q){
            $producto = inventario_sucursal::where("idinsucursal",$q->id_producto)->where("id_sucursal",$q->id_sucursal)->first();
            $q->producto = $producto;
            return $q;
      });
   }
}
