<?php

namespace App\Http\Controllers;

use App\Models\cajas_aprobacion;
use App\Http\Requests\Storecajas_aprobacionRequest;
use App\Http\Requests\Updatecajas_aprobacionRequest;
use Illuminate\Http\Request;


class CajasAprobacionController extends Controller
{
   function setPermisoCajas(Request $req) {

    $data = $req->data;
    $codigo_origen = $req->codigo_origen;

    $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
    
    $id_sucursal = $id_ruta["id_origen"];
    $concepto = $data["concepto"];
    $categoria = $data["categoria"];
    $montodolar = $data["montodolar"];
    $dolarbalance = $data["dolarbalance"];
    $montobs = $data["montobs"];
    $bsbalance = $data["bsbalance"];
    $montopeso = $data["montopeso"];
    $pesobalance = $data["pesobalance"];
    $montoeuro = $data["montoeuro"];
    $eurobalance = $data["eurobalance"];
    $estatus = $data["estatus"];
    $fecha = $data["fecha"];
    $tipo = $data["tipo"];
    $idinsucursal = $data["idinsucursal"];

    $cajas_aprobacion = cajas_aprobacion::updateOrCreate([
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,
    ],[
        "concepto" => $concepto,
        "categoria" => $categoria,
        "montodolar" => $montodolar,
        "dolarbalance" => $dolarbalance,
        "montobs" => $montobs,
        "bsbalance" => $bsbalance,
        "montopeso" => $montopeso,
        "pesobalance" => $pesobalance,
        "montoeuro" => $montoeuro,
        "eurobalance" => $eurobalance,
        "estatus" => $estatus,
        "fecha" => $fecha,
        "tipo" => $tipo,
        "id_sucursal" => $id_sucursal,
        "idinsucursal" => $idinsucursal,
    ]);

    if ($cajas_aprobacion) {
        return "Solicitud enviada. Esperar aprobación...";
    }
   }
   

   function getAprobacionCajas($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $qestatusaprobaciocaja = $filtros["qestatusaprobaciocaja"];
        $data = cajas_aprobacion::with(["cat","sucursal"])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->where("estatus", $qestatusaprobaciocaja)
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->orderBy("created_at", "desc")
        ->get();

        return [
            "aprobacionfuertedata" => $data,
        ];
   }

   function aprobarMovCajaFuerte(Request $req) {
    $tipo = $req->tipo;
    $id = $req->id;

    switch ($tipo) {
        case 'delete':
            $mov = cajas_aprobacion::find($id)->delete();
            break;
            
        case 'aprobar':
            $mov = cajas_aprobacion::find($id);
            $mov->estatus = $mov->estatus==0?1:0;
            $mov->save();
        break;
    }

    if ($mov) {
        return "Éxito al ($tipo)";
    }
   }

   function verificarMovPenControlEfec(Request $req) {
    $codigo_origen = $req->codigo_origen;
    $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
    $id_sucursal = $id_ruta["id_origen"];

    return cajas_aprobacion::where("id_sucursal",$id_sucursal)->where("estatus",1)->get();

   }
}
