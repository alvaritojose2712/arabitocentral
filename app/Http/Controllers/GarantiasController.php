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
   function sendGarantias(Request $req) {
      /* $garantias = $req->garantias;
      $codigo_origen =  $req->codigo_origen;

      $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
      $id_origen = $id_ruta["id_origen"];

      $arr_ok = [];
      $num = 0;

      foreach ($garantias as $e) {
          $id_vinculacion = $e["producto"]["id_vinculacion"];
          if (inventario::find($id_vinculacion)) {

               $uoc = garantias::updateOrCreate([
                     "id_sucursal" => $id_origen,
                     "id_producto" => $id_vinculacion,
                     "id_cliente" => 1
               ],[
                     "cantidad" => $e["cantidad"],
                     "motivo" => "DF",
               ]);
               if ($uoc) {
                     $arr_ok[] = $e["id"];
                     $num++;
               }
          }

      }
      garantias::where("id_sucursal",$id_origen)->whereNotIn("id_local",$arr_ok)->delete();
      return $num." garantias cargadas de ".count($garantias); */
   }
}
