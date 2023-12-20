<?php

namespace App\Http\Controllers;

use App\Models\garantias;
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
                  "motivo" =>  "DF",
                  "id_cliente" => 1
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
}
