<?php

namespace App\Http\Controllers;

use App\Models\cierresGeneral;
use App\Models\catcajas;

use App\Http\Requests\StorecierresGeneralRequest;
use App\Http\Requests\UpdatecierresGeneralRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\enviarCierre;

use Response;


class CierresGeneralController extends Controller
{
    function sendReporteDiario(Request $req) {
        $fecha = $req->fecha;
        $type = $req->type;
        $sucursal = $req->sucursal;
        return $this->sendReporteFun($fecha,$type,$sucursal);
        
    }
    function sendReporteFun($fecha,$type,$sucursal) {

        
        $b = (new CierresController)->balanceGeneralFun(
            $sucursal,
            $fecha,
            $fecha  
        );
        $ingreso_credito = abs($b["ingreso_credito_sum"]);
        $cajamatriz = abs($b["matriz_actual"]);
        $prestamos = abs($b["prestamos_sum"]);
        $abono = abs($b["abonos_sum"]);
        $inventariobase = abs($b["final_inventariobase"]);
        $inventarioventa = abs($b["final_inventarioventa"]);
        $fdi = abs($b["fdi"]);
        $cxc = abs($b["cxc_final"]);
        
        $gastofijo = abs($b["gastosfijosSum"]) + abs($b["gastosGeneralesfijosSum"]);
        $gastovariable = abs($b["gastosvariablesSum"]) + abs($b["gastosGeneralesvariablesSum"]);
        
        $cuotacredito = abs($b["cuota_credito_sum"]);
        $comisioncredito = abs($b["comision_credito_sum"]);
        $interescredito = abs($b["interes_credito_sum"]);
        
        $utilidadbruta = ($b["ganancia"]);
        $debito = abs($b["debito"]);
        $transferencia = abs($b["transferencia"]);
        $biopago = abs($b["biopago"]);
        $efectivo = abs($b["efectivo"]);
        
        $debitobs = abs($b["debitobs"]);
        $transferenciabs = abs($b["transferenciabs"]);
        $biopagobs = abs($b["biopagobs"]);
        
        $cxp = abs($b["cxp"]);
        $utilidadneta = ($b["gananciaNeta"]);
        
        $pagoproveedor = abs($b["sumPagoProveedorEfectivo"]);
        $pagoproveedorbancodivisa = abs($b["sumPagoProveedorBancoDivisa"]);
        
        
        $perdidatasa = abs($b["perdidaPagoProveedor"]);
        $numsucursales = count($b["cierresUltimo"]["data"]);
        
        $cajaregistradora = abs($b["sum_caja_regis_actual"]);
        $cajachica = abs($b["sum_caja_chica_actual"]);
        $cajafuerte = abs($b["sum_caja_fuerte_actual"]);
        $numventas = abs($b["numventas"]);
        
        $bancobs = abs($b["sum_caja_actual_banco"]);
        $bancodivisa = abs($b["sum_caja_actual_banco_dolar"]);
        $nomina = $b["numnomina"];
        
        $pagoproveedorbs = abs($b["sumPagoProveedorBancoBs"]);
        $pagoproveedorbsbs = $b["sumPagoProveedorBancoBsBs"];
        $pagoproveedortasapromedio = $b["sumPagoProveedorBancoTasaPromedio"];
        
        /////////////
        
        $checkEstado = cierresGeneral::where("fecha",$fecha)->where("estado",1)->first();
        if (!$checkEstado) {
            $cierresGeneral = cierresGeneral::updateOrCreate([
                "fecha" => $fecha,
            ],[
                "cxp" => $cxp,
                "cxc" => $cxc,
                "prestamos" => $prestamos,
                "abono" => $abono,
                "gastofijo" => $gastofijo,
                "gastovariable" => $gastovariable,
                "cuotacredito" => $cuotacredito,
                "comisioncredito" => $comisioncredito,
                "interescredito" => $interescredito,
                "fdi" => $fdi,
                "perdidatasa" => $perdidatasa,
                "pagoproveedor" => $pagoproveedor,
                "pagoproveedorbancodivisa" => $pagoproveedorbancodivisa,
                "pagoproveedorbs" => $pagoproveedorbs,
                "pagoproveedorbsbs" => $pagoproveedorbsbs,
                "pagoproveedortasapromedio" => $pagoproveedortasapromedio,
                
                "ingreso_credito" => $ingreso_credito,
                "debito" => $debito,
                "debitobs" => $debitobs,
                "transferencia" => $transferencia,
                "transferenciabs" => $transferenciabs,
                "biopago" => $biopago,
                "biopagobs" => $biopagobs,
                "efectivo" => $efectivo,
                "utilidadbruta" => $utilidadbruta,
                "utilidadneta" => $utilidadneta,
                "cajaregistradora" => $cajaregistradora,
                "cajachica" => $cajachica,
                "cajafuerte" => $cajafuerte,
                "cajamatriz" => $cajamatriz,
                "bancobs" => $bancobs,
                "bancodivisa" => $bancodivisa,
                "inventariobase" => $inventariobase,
                "inventarioventa" => $inventarioventa,
                "numventas" => $numventas,
                "nomina" => $nomina,
                "numsucursales" => $numsucursales,
                "estado" => 1
            ]);
        }

        $c = cierresGeneral::where("fecha",$fecha)->first();
        
        $c->catcajas = catcajas::all()->groupBy("id");
        $c->ingresosData = $b["cierresUltimo"]["data"];
        $c->gastos = $b["gastos"];
        $c->sumArrcat = $b["sumArrcat"];
        $c->sumArrcatgeneral = $b["sumArrcatgeneral"];
        $c->sumArringresoegreso = $b["sumArringresoegreso"];
        $c->sumArrvariablefijo = $b["sumArrvariablefijo"];

        $c->fdidata = $b["fdidata"];

        $c->ingreso_credito_data = $b["ingreso_credito_data"];
        $c->cuota_credito_data = $b["cuota_credito_data"];
        $c->comision_credito_data = $b["comision_credito_data"];
        $c->interes_credito_data = $b["interes_credito_data"];
        $c->pagoproveedorData = $b["pagoproveedor"];

        $c->matriz_actual = $b["matriz_actual"];
        $c->bancoData = $b["bancoData"];
        
        $c->caja_actual = $b["caja_actual"];

        $c->sum_caja_regis_actual = $b["sum_caja_regis_actual"];
        $c->sum_caja_chica_actual = $b["sum_caja_chica_actual"];
        $c->sum_caja_fuerte_actual = $b["sum_caja_fuerte_actual"];
        
        $c->sum_caja_actual = $b["sum_caja_actual"];
        $c->caja_actual_banco = $b["caja_actual_banco"];
        
        if ($type=="ver") {
            return view("reportes.cierregeneral", $c);
        }else if($type=="enviar"){
            $from1 = "arabitoferreteria@gmail.com";
            $from = "ARABITO CENTRAL";
            $subject = "ARABITO CENTRAL | " . $fecha;
            $data = ($c)->toArray();
            $arr_send = ["omarelhenaoui@gmail.com","alvaroospino79@gmail.com"];
            try {
    
                Mail::to($arr_send)->send(new enviarCierre($data, $from1, $from, $subject));
    
                return Response::json(["msj" => "Cierre enviado", "estado" => true]);
    
            } catch (\Exception $e) {
    
                return Response::json(["msj" => "Error: " . $e->getMessage(), "estado" => false]);
    
            }
        }
    }

}
