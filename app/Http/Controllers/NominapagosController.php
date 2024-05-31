<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\nomina;
use App\Models\creditos;

use App\Models\cajas;
use Illuminate\Http\Request;

use App\Models\nominapagos;
use App\Http\Requests\StorenominapagosRequest;
use App\Http\Requests\UpdatenominapagosRequest;
use Response;
class NominapagosController extends Controller
{
    function configPagos(){
        $cajas = cajas::with(["cat","sucursal"])->orderBy("fecha","asc")->get();
        $num_nulls = 0;
        foreach ($cajas as $key => $e) {
            if ($e["cat"]) {
                $catnombre = $e["cat"]["nombre"];
                $id_sucursal = $e["id_sucursal"];
                if (strpos($catnombre,"NOMINA")) {
                    $split = explode("=",$e["concepto"]);
                    if (isset($split[1])) {
                        $ci = $split[1];
                        $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:$e["montopeso"]);
                        (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                    }
                    
                }
            }else{
                $num_nulls++;
            }
        }
        return $num_nulls." IGNORADOS DE ".$cajas->count();
    }

    function getHistoricoNomina($ci) {
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

        $n = nomina::with(["cargo","prestamos", "pagos"=>function ($q) {
            $q->with("sucursal")->orderBy("created_at","asc");
        }])
        ->selectRaw("*, round(DATEDIFF(NOW(), nominas.nominafechadenacimiento)/365.25, 2) as edad, round(DATEDIFF(NOW(), nominas.nominafechadeingreso)/365.25, 2) as tiempolaborado")
        ->where("nominacedula",$ci)
        ->first();

        if ($n) {
            $id_cliente = clientes::where("identificacion",$ci)->first("id");
            $creditos = creditos::with("sucursal")->where("id", $id_cliente?$id_cliente->id:null);
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
            $n->maxpagopersona = (floatval($bono)*2)-(abs(floatval($mesSum)))>0?(floatval($bono)*2)-(abs(floatval($mesSum))):0;
            
            $n->creditos = $creditos
            ->get()
            ->map(function($q) {
                $q->created_at = date("d-m-Y", strtotime($q->created_at));
                return $q;
            }); 
            $n->sumCreditos = $creditos->get()->sum("saldo");
        }
        return $n;
    }
    function setPagoNomina($ci, $monto, $id_sucursal, $idinsucursal,$fecha="") {

        $id_nomina = nomina::where("nominacedula",$ci)->first("id");
        if ($id_nomina) {
            $id_nomina = $id_nomina->id;

            return nominapagos::updateOrCreate([
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
            ],[
                "monto" => $monto,
                "descripcion" => "PAGO NOMINA",
                "id_nomina" => $id_nomina,
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
                "created_at" => $fecha?$fecha:date("Y-m-d H:i:s"),
            ]);
        }
    }
}
