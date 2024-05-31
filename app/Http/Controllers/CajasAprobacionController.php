<?php

namespace App\Http\Controllers;

use App\Models\cajas_aprobacion;
use App\Http\Requests\Storecajas_aprobacionRequest;
use App\Http\Requests\Updatecajas_aprobacionRequest;
use App\Models\catcajas;
use App\Models\nomina;
use App\Models\creditos;


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

    $id_sucursal_destino = isset($data["id_sucursal_destino"])?$data["id_sucursal_destino"]:null;

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
        "id_sucursal_destino"=>$id_sucursal_destino,
        "id_sucursal_emisora"=>$id_sucursal,
    ]);

    if ($cajas_aprobacion) {
        return ["msj"=>"Solicitud enviada. Esperar aprobación...", "idincentralrecepcion"=>$cajas_aprobacion->id];
    }
   }
   function checkDelMovCajaCentral(Request $req) {
        $idincentral = $req->idincentral;
        $c = cajas_aprobacion::find($idincentral);
        if ($c) {
            if ($c->estatus==1 || $c->sucursal_destino_aprobacion==1) {
                return ["estado"=>false,"msj"=>"Aprobado. No se puede eliminar"];
            }
        }else{
            if ($c) {$c->delete();}
            return ["estado"=>true,"msj"=>"No se encontró movimiento!"];
        }
        if ($c) {$c->delete();}
        return ["estado"=>true,"msj"=>"Si puedes Eliminar"];
   }

   function getAprobacionCajas($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $qestatusaprobaciocaja = $filtros["qestatusaprobaciocaja"];
        $data = cajas_aprobacion::with(["cat","sucursal","destino"])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->where("estatus", $qestatusaprobaciocaja)
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->orderBy("created_at", "desc")
        ->get()
        ->map(function($q) {
            if ($q["cat"]) {
                if (strpos($q["cat"]["nombre"],"NOMINA")) {
                    $split = explode("=",$q["concepto"]);
                    if (isset($split[1])) {
                        $ci = $split[1];
                        $q->trabajador = (new NominapagosController)->getHistoricoNomina($ci);
                    }
                }
            }
            return $q;    
        });

        return [
            "aprobacionfuertedata" => $data,
        ];
   }

   function aprobarMovCajaFuerte(Request $req) {
    $tipo = $req->tipo;
    $id = $req->id;

    switch ($tipo) {
        case 'delete':
            $mov = cajas_aprobacion::find($id);
            if ($mov->sucursal_destino_aprobacion!=1) {
                $mov->delete();
            }
            break;
            
        case 'aprobar':
            $mov = cajas_aprobacion::find($id);
            $usuario = session("usuario");
            $cat = catcajas::find($mov->categoria);
            /* if ($cat) {
                if (str_contains($cat->nombre,"TRASPASO A CAJA MATRIZ")) {
                    if ($usuario!=="raidh") {
                        return "CAJA MATRIZ SOLO APRUEBA RAID";
                    }
                }
            } */

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
    $today = (new NominaController)->today();

    $c = cajas_aprobacion::with(["destino","sucursal"])
    ->where("id_sucursal",$id_sucursal)
    ->where("estatus",1)
    ->where("fecha",$today)
    ->get();

    $aprotrans = cajas_aprobacion::with(["destino","sucursal"])
    ->where("id_sucursal_destino",$id_sucursal)
    ->where("estatus",1)
    ->where("fecha",$today)
    ->get();

    $pen = cajas_aprobacion::where("id_sucursal_destino",$id_sucursal)
    ->where("estatus",0)
    ->where("fecha",$today)
    ->get();
    
    $transSucursal = [];
    foreach ($pen as $i => $e) {
        if ($e->id_sucursal_destino && !$e->sucursal_destino_aprobacion) {
            array_push($transSucursal,$e);
        }
    }
    if (count($transSucursal)) {
        return ["pendientesTransferencia"=>true,"data"=>$transSucursal];
    }
    return $c->merge($aprotrans);

   }

   function aprobarRecepcionCaja(Request $req) {
    $id = $req->id;
    $type = $req->type;

    $c = cajas_aprobacion::find($id);
    if ($c) {
        if ($c->estatus) {
            return ["msj"=>"Desde Central: No puede modificar decisión. Movimiento aprobado.", "estado"=>false, "type"=>null]; 
        }
        $t = ($type=="aprobar"?1:null);
        $c->sucursal_destino_aprobacion = $t; 
        if ($c->save()) {
            return ["msj"=>"Desde Central: Éxito al ejecutar decisión", "estado"=>true, "type"=>$t]; 
        }else{
            return ["msj"=>"Desde Central: Error al ejecutar decisión", "estado"=>false, "type"=>$t]; 
        }
    }
}
}
