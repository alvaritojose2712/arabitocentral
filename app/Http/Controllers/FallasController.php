<?php

namespace App\Http\Controllers;

use App\Models\fallas;
use App\Models\sucursal;
use App\Models\inventario;

use App\Http\Requests\StorefallasRequest;
use App\Http\Requests\UpdatefallasRequest;
use Illuminate\Http\Request;
use Response;


class FallasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFallas(Request $req)
    {

        $fallas = $req->fallas;

        $codigo_origen =  $req->codigo_origen;

        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
        $id_origen = $id_ruta["id_origen"];

        $arr_ok = [];
        $num = 0;
        foreach ($fallas as $e) {
            $id_vinculacion = $e["producto"]["id_vinculacion"];
            if (inventario::find($id_vinculacion)) {
                $uoc = fallas::updateOrCreate([
                    "id_local" => $e["id"],
                    "id_sucursal" => $id_origen,
                ],[
                    "id_producto" => $id_vinculacion,
                    "stockmin" => $e["producto"]["stockmin"],
                    "cantidad" => $e["producto"]["cantidad"],
                ]);
                if ($uoc) {
                    $arr_ok[] = $e["id"];
                    $num++;
                }
            }

        }
        fallas::where("id_sucursal",$id_origen)->whereNotIn("id_local",$arr_ok)->delete();
        return $num." fallas cargadas de ".count($fallas);
    }

    public function getFallas(Request $req)
    {
        return fallas::with("producto")->where("id_sucursal",$req->id_sucursal)->get();
    }


}
