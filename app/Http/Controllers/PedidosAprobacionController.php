<?php

namespace App\Http\Controllers;

use App\Models\pedidos_aprobacion;
use Illuminate\Http\Request;
use Response;

class PedidosAprobacionController extends Controller
{

    function createAnulacionPedidoAprobacion(Request $req) {
        $data = $req->data;
        $codigo_origen = $req->codigo_origen;

        $id_sucursal = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen)["id_origen"];
        $id = $data["id"]; 
        $monto = $data["monto"]; 
        $items = $data["items"]; 
        $pagos = $data["pagos"]; 
        $motivo = $data["motivo"];
        $cliente = $data["cliente"];

        $check = pedidos_aprobacion::where("idinsucursal",$id)->where("id_sucursal",$id_sucursal)->first("estatus");
        if ($check) {
            if ($check->estatus==1) {
                return ["estado" =>true, "msj" => "APROBADO"];
            }
        }


        $count_createTrans = 0;
        

        $pedidos_aprobacion = pedidos_aprobacion::updateOrCreate([
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $id,
        ],[
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $id,
            "monto" => $monto,
            "estatus" => 0,
            "items" => $items,
            "pagos" => $pagos,

            "motivo" => $motivo,
            "cliente" => $cliente,
        ]);
        
        return ["estado" =>false, "msj" => "DESDE CENTRAL: $count_createTrans Solicitud enviada. En espera de aprobación..."];
        
    }
    function getAprobacionPedidoAnulacion(Request $req) {
        $qnumPedidoAnulacionAprobacion = $req->qnumPedidoAnulacionAprobacion;
        $id_sucursal = $req->sucursalPedidoAnulacionAprobacion;
        
        $qdesdePedidoAnulacionAprobacion = $req->qdesdePedidoAnulacionAprobacion;
        $qhastaPedidoAnulacionAprobacion = $req->qhastaPedidoAnulacionAprobacion;
        $qestatusPedidoAnulacionAprobacion = $req->qestatusPedidoAnulacionAprobacion;

        $data = pedidos_aprobacion::with(["sucursal"])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->when($qnumPedidoAnulacionAprobacion, function($q) use ($qnumPedidoAnulacionAprobacion){
            $q->where("idinsucursal",$qnumPedidoAnulacionAprobacion);
        })
        ->where("estatus", $qestatusPedidoAnulacionAprobacion)
        ->whereBetween("created_at", [$qdesdePedidoAnulacionAprobacion." 00:00:00", $qhastaPedidoAnulacionAprobacion." 23:59:59"])
        ->orderBy("created_at", "desc")
        ->get();

        return $data;
    }
    
    
    function setAprobacionPedidoAnulacion(Request $req) {
        $tipo = $req->tipo;
        $id = $req->id;

        switch ($tipo) {
            case 'delete':
                $mov = pedidos_aprobacion::find($id);
                $mov->estatus = 2;
                $mov->save();
                break;
                
            case 'aprobar':
                $mov = pedidos_aprobacion::find($id);
                $mov->estatus = $mov->estatus==0?1:0;
                $mov->save();
            break;
        }

        if ($mov) {
            return "Éxito al ($tipo)";
        }
    }
}
