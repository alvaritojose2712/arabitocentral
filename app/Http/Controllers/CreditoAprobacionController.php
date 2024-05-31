<?php

namespace App\Http\Controllers;

use App\Models\credito_aprobacion;
use App\Models\clientes;
use App\Models\nomina;
use App\Models\creditos;



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
        $fecha_ultimopago = "2000-01-01";
        if (isset($data["deuda"]["pedido_total"]["diferencia_clean"])) {
            $deuda = $data["deuda"]["pedido_total"]["diferencia_clean"];
        }
        if (isset($data["fecha_ultimopago"])) {
            $fecha_ultimopago = $data["fecha_ultimopago"];
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
                    "fecha_ultimopago" => $fecha_ultimopago,
                ]);
                if ($credito_aprobacion) {

                    return "Solicitud enviada. Esperar aprobación de Crédito...";
                }
            }
        }
        
    }
    function getCreditoAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $qestatus = $filtros["qestatusaprobaciocaja"];
        

        $today = (new NominaController)->today();
        $mesDate = strtotime($today);
        $mesDate = date('Y-m' , $mesDate);

        $mespasadoDate = strtotime('-1 months', strtotime($today));
        $mespasadoDate = date('Y-m' , $mespasadoDate);

        $mesantepasadoDate = strtotime('-2 months', strtotime($today));
        $mesantepasadoDate = date('Y-m' , $mesantepasadoDate);

        $mes = $mesDate;
        $mespasado = $mespasadoDate;
        $mesantepasado = $mesantepasadoDate;

        $data = credito_aprobacion::with(["sucursal","cliente"])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->where("estatus", $qestatus)
        ->whereBetween("created_at", [$fechasMain1." 00:00:00", $fechasMain2." 23:59:59"])
        ->orderBy("created_at", "desc")
        ->get()
        ->map(function($q) use ($mes,$mespasado,$mesantepasado) {
            $cedula = $q->cliente->identificacion;
            $n = nomina::with(["cargo","prestamos", "pagos"=>function ($q) {
                $q->with("sucursal")->orderBy("created_at","asc");
            }])
            ->selectRaw("*, round(DATEDIFF(NOW(), nominas.nominafechadenacimiento)/365.25, 2) as edad, round(DATEDIFF(NOW(), nominas.nominafechadeingreso)/365.25, 2) as tiempolaborado")
            ->where("nominacedula",$cedula)
            ->first();

            if ($n) {
                $creditos = creditos::with("sucursal")->where("id",$q->id_cliente);
                $n->pagos = $n->pagos->map(function($q) {
                    $q->created_at = date("d-m-Y", strtotime($q->created_at));
                    return $q;
                });
    
                $pagos = $n->pagos;
                $mesSum = 0;
                $mespasadoSum = 0;
                $mesantepasadoSum = 0;
                foreach ($pagos as $pago) {
                    if (str_contains($pago["created_at"],$mes)) {
                        $mesSum += $pago["monto"];
                    }
                    if (str_contains($pago["created_at"],$mespasado)) {
                        $mespasadoSum += $pago["monto"];
                    }
                    if (str_contains($pago["created_at"],$mesantepasado)) {
                        $mesantepasadoSum += $pago["monto"];
                    }
                }
                $bono = $n["cargo"]["cargossueldo"];
                
                $n->mes = $mesSum;
                $n->mespasado = $mespasadoSum;
                $n->mesantepasado = $mesantepasadoSum;
                $n->bono = $bono;
                $n->quincena = $bono;
                $n->sumprestamos = $n->prestamos->sum("monto");
                $n->sumPagos = $pagos->sum("monto");
                $n->maxpagopersona = ($bono*2)-(abs($mesSum)?abs($mesSum):0)>0?($bono*2)-(abs($mesSum)?abs($mesSum):0):0;
                
                $n->creditos = $creditos
                ->get()
                ->map(function($q) {
                    $q->created_at = date("d-m-Y", strtotime($q->created_at));
                    return $q;
                }); 
                $n->sumCreditos = $creditos->get()->sum("saldo");
            }

            $q->trabajador = $n;
            return $q;
        });

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
