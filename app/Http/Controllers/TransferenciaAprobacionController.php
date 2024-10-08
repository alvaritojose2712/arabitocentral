<?php

namespace App\Http\Controllers;

use App\Models\transferencia_aprobacion;
use App\Models\bancos_list;

use App\Http\Requests\Storetransferencia_aprobacionRequest;
use App\Http\Requests\Updatetransferencia_aprobacionRequest;
use Illuminate\Http\Request;

class TransferenciaAprobacionController extends Controller
{
        function createTranferenciaAprobacion(Request $req) {
            $data = $req->data;
            $codigo_origen = $req->codigo_origen;
    
            $id_sucursal = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen)["id_origen"];
            /* $idinsucursal = $data["idinsucursal"];
            $saldo = $data["saldo"];
            $loteserial = $data["loteserial"];
            $banco = $data["banco"]; */

            $count_refs = count($data["refs"]);
            $count_apro = 0;

            foreach ($data["refs"] as $ref) {
                $check = transferencia_aprobacion::where("idinsucursal",$ref["id"])->where("id_sucursal",$id_sucursal)->first("estatus");
                if ($check) {
                    if ($check->estatus==1) {
                        $count_apro++;
                    }
                }
            }

            if ($count_refs==$count_apro) {
                return ["estado" =>true, "msj" => "APROBADO", "count_refs"=>$count_refs, "count_apro"=>$count_apro,"idinsucursal"=>$ref["id"],"id_sucursal"=>$id_sucursal,"dataRef"=>$data["refs"]];
            }

            $count_createTrans = 0;


            $retenciones = isset($data["retenciones"])?$data["retenciones"]:[];
            $sumRetenciones = 0;
            
            foreach ($retenciones as $i => $retencion) {
                $sumRetenciones += $retencion["monto"];
            }

            foreach ($data["refs"] as $ref) {
                $id_banco = bancos_list::where("codigo",$ref["banco"])->first()->id;

                $transferencia_aprobacion = transferencia_aprobacion::updateOrCreate([
                    "id_sucursal" => $id_sucursal,
                    "idinsucursal" => $ref["id"],
                ],[
                    "id_sucursal" => $id_sucursal,
                    "idinsucursal" => $ref["id"],
                    "estatus" => 0,
                    "saldo" => $ref["monto"],
                    "loteserial"=>$ref["descripcion"],
                    "banco"=>$ref["banco"],
                    "id_banco"=>$id_banco,
                    "montoretencion" => ($sumRetenciones/$count_refs),
                    "estadoretencion" => 0,
                ]);
                if ($transferencia_aprobacion) {
                    $count_createTrans++;
                }
            }
            return ["estado" =>false, "msj" => "DESDE CENTRAL: $count_createTrans Referencias enviadas. En espera de aprobación..."];
            
        }
        function gettransferenciaAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
            $qestatus = $filtros["qestatusaprobaciocaja"];
            $qfiltroaprotransf = $filtros["qfiltroaprotransf"];
            $bancoqfiltroaprotransf = $filtros["bancoqfiltroaprotransf"];

            $data = transferencia_aprobacion::with(["sucursal"])
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("id_sucursal", $id_sucursal);
            })
            ->when($qfiltroaprotransf, function($q) use ($qfiltroaprotransf){
                $q->where(function($q)  use ($qfiltroaprotransf) {
                    $q->orwhere("loteserial","LIKE", "%$qfiltroaprotransf%")
                    ->orwhere("saldo","LIKE", "%$qfiltroaprotransf%");
                });
            })
            ->when($bancoqfiltroaprotransf, function($q) use ($bancoqfiltroaprotransf){
                $q->where("banco",$bancoqfiltroaprotransf);
            })
            ->where("estatus", $qestatus)
            ->whereBetween("created_at", [$fechasMain1." 00:00:00", $fechasMain2." 23:59:59"])
            ->orderBy("created_at", "desc")
            ->get();
    
            return [
                "aprobaciontransferenciasdata" => $data,
            ];
        }
    
        function aprobarTransferenciaFun(Request $req) {
            $tipo = $req->tipo;
            $id = $req->id;
    
            switch ($tipo) {
                case 'delete':
                    $mov = transferencia_aprobacion::find($id);
                    $mov->estatus = 2;
                    $mov->save();
                    break;
                    
                case 'aprobar':
                    $mov = transferencia_aprobacion::find($id);
                    $mov->estatus = $mov->estatus==0?1:0;
                    $mov->save();
                break;
            }
    
            if ($mov) {
                return "Éxito al ($tipo)";
            }
        }
}
