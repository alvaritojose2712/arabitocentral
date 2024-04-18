<?php

namespace App\Http\Controllers;

use App\Models\credito_aprobacion;
use App\Models\clientes;

use App\Http\Requests\Storecredito_aprobacionRequest;
use App\Http\Requests\Updatecredito_aprobacionRequest;
use Illuminate\Http\Request;
use Response;

class CreditoAprobacionController extends Controller
{
    function createCreditoAprobacion(Request $req) {
        $data = $req->data;
        $codigo_origen = $req->codigo_origen;

        $id_sucursal = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen)["id_origen"];
        $idinsucursal = $data["idinsucursal"];
        $saldo = $data["saldo"];
        $deuda = 0;
        if (isset($data["deuda"]["pedido_total"]["diferencia_clean"])) {
            $deuda = $data["deuda"]["pedido_total"]["diferencia_clean"];
        }
        
        $check = credito_aprobacion::where("idinsucursal",$idinsucursal)->where("id_sucursal",$id_sucursal)->first("estatus");
        if ($check) {
            if ($check->estatus==1) {
                return "APROBADO";
            }
        }

        if ($data["cliente"]) {
            $cliente = $data["cliente"];
            $c = str_replace(["-", ".", "v", "V", " "], ["","","","",""], $cliente["identificacion"]);        
            $id_cliente = clientes::updateOrCreate([
                "identificacion" => $c,
            ],[
                "identificacion" => $c,
                "nombre" => $cliente["nombre"],
                "correo" => $cliente["correo"],
                "direccion" => $cliente["direccion"],
                "telefono" => $cliente["telefono"],
                "estado" => $cliente["estado"],
                "ciudad" => $cliente["ciudad"],
            ]);
    
            if ($id_cliente) {
                $credito_aprobacion = credito_aprobacion::updateOrCreate([
                    "id_sucursal" => $id_sucursal,
                    "idinsucursal" => $idinsucursal,
                ],[
                    "id_sucursal" => $id_sucursal,
                    "idinsucursal" => $idinsucursal,
                    "id_cliente" => $id_cliente->id,
                    "estatus" => 0,
                    "saldo" => $saldo,
                    "deuda" => $deuda,
                ]);
                if ($credito_aprobacion) {

                    return "Solicitud enviada. Esperar aprobación de Crédito...";
                }
            }
        }
        
    }
    function getCreditoAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $qestatus = $filtros["qestatusaprobaciocaja"];
        
        $data = credito_aprobacion::with(["sucursal","cliente"])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->where("estatus", $qestatus)
        ->whereBetween("created_at", [$fechasMain1." 00:00:00", $fechasMain2." 23:59:59"])
        ->orderBy("created_at", "desc")
        ->get();

        return [
            "aprobacioncreditosdata" => $data,
        ];
    }

    function aprobarCreditoFun(Request $req) {
        $tipo = $req->tipo;
        $id = $req->id;

        switch ($tipo) {
            case 'delete':
                $mov = credito_aprobacion::find($id)->delete();
                break;
                
            case 'aprobar':
                $mov = credito_aprobacion::find($id);
                $mov->estatus = $mov->estatus==0?1:0;
                $mov->save();
            break;
        }

        if ($mov) {
            return "Éxito al ($tipo)";
        }
    }
}
