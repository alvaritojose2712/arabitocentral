<?php

namespace App\Http\Controllers;

use App\Models\transferencia_aprobacion;
use App\Http\Requests\Storetransferencia_aprobacionRequest;
use App\Http\Requests\Updatetransferencia_aprobacionRequest;
use Illuminate\Http\Request;

class TransferenciaAprobacionController extends Controller
{
        function createtransferenciaAprobacion(Request $req) {
            $data = $req->data;
            $codigo_origen = $req->codigo_origen;
    
            $id_sucursal = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen)["id_origen"];
            $idinsucursal = $data["idinsucursal"];
            $saldo = $data["saldo"];
            $loteserial = $data["loteserial"];
            $banco = $data["banco"];
            
            $check = transferencia_aprobacion::where("idinsucursal",$idinsucursal)->where("id_sucursal",$id_sucursal)->first("estatus");
            if ($check) {
                if ($check->estatus==1) {
                    return "APROBADO";
                }
            }
    
            $transferencia_aprobacion = transferencia_aprobacion::updateOrCreate([
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
            ],[
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
                "estatus" => 0,
                "saldo" => $saldo,
                "loteserial"=>$loteserial,
                "banco"=>$banco,
            ]);
            if ($transferencia_aprobacion) {

                return "Solicitud enviada. Esperar aprobación de Transferencia...";
            }
            
        }
        function gettransferenciaAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
            $qestatus = $filtros["qestatusaprobaciocaja"];
            
            $data = transferencia_aprobacion::with(["sucursal","cliente"])
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("id_sucursal", $id_sucursal);
            })
            ->where("estatus", $qestatus)
            ->whereBetween("created_at", [$fechasMain1." 00:00:00", $fechasMain2." 23:59:59"])
            ->orderBy("created_at", "desc")
            ->get();
    
            return [
                "aprobaciontransferenciasdata" => $data,
            ];
        }
    
        function aprobartransferenciaFun(Request $req) {
            $tipo = $req->tipo;
            $id = $req->id;
    
            switch ($tipo) {
                case 'delete':
                    $mov = transferencia_aprobacion::find($id)->delete();
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
