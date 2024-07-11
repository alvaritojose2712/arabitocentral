<?php

namespace App\Http\Controllers;

use App\Models\cajas;
use App\Http\Requests\StorecajasRequest;
use App\Http\Requests\UpdatecajasRequest;
use App\Models\catcajas;
use App\Models\cierres;
use App\Models\cuentasporpagar;
use App\Models\puntosybiopagos;
use App\Models\bancos_list;


use App\Models\proveedores;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Response;
class CajasController extends Controller
{

    function getDisponibleEfectivoSucursal() {
        $arr = [];
        $dolarbalance = 0;
        $bsbalance = 0;
        $pesobalance = 0;
        $eurobalance = 0;
        $su = sucursal::orderByRaw("FIELD(id,1,2,5,4,3,6,7,15,8,9,10,11,16,12,14)")->get();

        foreach ($su as $sucursal) {
            $c = cajas::with("sucursal")->where("id_sucursal",$sucursal->id)->where("concepto","LIKE","%INGRESO DESDE CIERRE%")->orderBy("fecha","desc")->first();
            if ($c) {
                array_push($arr, $c);
                $dolarbalance += $c->dolarbalance;
                $bsbalance += $c->bsbalance;
                $pesobalance += $c->pesobalance;
                $eurobalance += $c->eurobalance;
            }
        }
        //array_multisort(array_column($arr,"id_sucursal"), SORT_ASC, $arr);
        return [
            "data" => $arr,
            "dolarbalance" => $dolarbalance,
            "bsbalance" => $bsbalance,
            "pesobalance" => $pesobalance,
            "eurobalance" => $eurobalance,
        ];
    }
    function setEfecFromSucursalToCentral($movs, $id_sucursal) {

            $bs = (new CierresController)->getTasa()["bs"];
            $cop = (new CierresController)->getTasa()["cop"];
        
            $count_movs = count($movs);
            $counter =0;
            $last = 0;
            if ($count_movs) {
                foreach ($movs as $e) {
                    if ($last<$e["id"]) {
                        $last=$e["id"];
                    }
    
                    $catnombre = $e["cat"]["nombre"];
                    $cattipo = $e["cat"]["tipo"];
                    $catindice = $e["cat"]["id"];
                    $checkcatcajas = catcajas::where("nombre",$catnombre)->where("tipo",$cattipo)->first();
                    if ($checkcatcajas) {
                        $setcategoria = $checkcatcajas->id;
                    }else{
                        $setcategoria = $catindice; 
                    }
    
                    if (strpos($catnombre,"NOMINA QUINCENA")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[1])) {
                            $ci = $split[1];
                            $monto = $e["montodolar"];
                            $monto += $e["montobs"]/$bs;
                            $monto += $e["montopeso"]/$cop;
                            $monto += $e["montoeuro"];
                            (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                        }
                    }

                    if (strpos($catnombre,"NOMINA ABONO") || strpos($catnombre,"NOMINA PRESTAMO")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[1])) {
                            $ci = $split[1];
                            $monto = $e["montodolar"];
                            $monto += round($e["montobs"]/$bs,2);
                            $monto += round($e["montopeso"]/$cop,2);
                            $monto += $e["montoeuro"];

                            if (strpos($catnombre,"NOMINA ABONO")) {
                                $monto = abs($monto);
                            }
                            (new NominaprestamosController)->setPrestamoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                        }
                    }
                    
                    if (strpos($catnombre,"PAGO PROVEEDOR")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[2])) {
                            $id_proveedor_caja = $split[2];
    
                            $pro = proveedores::find($id_proveedor_caja);
                            if ($pro) {
                                $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:($e["montopeso"]?$e["montoeuro"]:0));
                                $monto = $monto*-1;
                                $idinsucursal_pago = "PAGO_".$id_proveedor_caja."_".$e["id"];
                                $fecha_creada = date("Y-m-d", strtotime($e["created_at"]));
                                $numfact_desc = "PAGO ".$pro->descripcion." ".$fecha_creada;
    
                                (new CuentasporpagarController)->setPago([
                                    "id_sucursal" => $id_sucursal,
                                    "idinsucursal_pago" => $idinsucursal_pago,
                                    "id_proveedor_caja" => $id_proveedor_caja,
                                    "numfact_desc" => $numfact_desc,
                                    "monto" => $monto,
                                    "fecha_creada" => $fecha_creada,
                                ]);
                            }
                        }
                    }
                    /* if (strpos($catnombre,"TODAS SUCURSALES")) {
                        $todas_sucursales = sucursal::where("codigo","<>","administracion")->get();
                        $divisor = $todas_sucursales->count(); 
                        foreach ($todas_sucursales as $key => $sucursal) {
                            $arr_insert = [
                                
                                "montodolar" => $e["montodolar"]/$divisor,
                                "montobs" => $e["montobs"]/$divisor,
                                "montopeso" => $e["montopeso"]/$divisor,
                                "montoeuro" => $e["montoeuro"]/$divisor,
                                
                                "dolarbalance" => $sucursal["id"]==$id_sucursal? $e["dolarbalance"]:0,
                                "bsbalance" => $sucursal["id"]==$id_sucursal? $e["bsbalance"]:0,
                                "pesobalance" => $sucursal["id"]==$id_sucursal? $e["pesobalance"]:0,
                                "eurobalance" => $sucursal["id"]==$id_sucursal? $e["eurobalance"]:0,
                                
                                "concepto" => $e["concepto"]." - FRACCION 1/".$divisor,
                                "categoria" => $setcategoria,
                                
                                "fecha" => $e["fecha"],
                                "tipo" => $e["tipo"],
                                "id_sucursal" => $sucursal["id"],
                                "idinsucursal" => $e["id"].$sucursal["id"],
                            ] ; 
                            $cc =  cajas::updateOrCreate([
                                "id_sucursal" => $sucursal["id"],
                                "idinsucursal" => $e["id"].$sucursal["id"],
                            ],$arr_insert);
                        }
                    }else{ */
                        $arr_insert = [
                            "montoeuro" => $e["montoeuro"],
                            "eurobalance" => $e["eurobalance"],
        
                            "concepto" => $e["concepto"],
                            "categoria" => $setcategoria,
                            "montodolar" => $e["montodolar"],
                            "montopeso" => $e["montopeso"],
                            "montobs" => $e["montobs"],
                            "dolarbalance" => $e["dolarbalance"],
                            "pesobalance" => $e["pesobalance"],
                            "bsbalance" => $e["bsbalance"],
                            "fecha" => $e["fecha"],
                            "tipo" => $e["tipo"],
                            "id_sucursal" => $id_sucursal,
                            "idinsucursal" => $e["id"],
                        ] ; 
                        $cc =  cajas::updateOrCreate([
                            "id_sucursal" => $id_sucursal,
                            "idinsucursal" => $e["id"],
                            
                        ],$arr_insert);
                    /* } */
    
                    if ($cc) {
                        $counter++;
                    }
                }
                return [
                    "msj" => "OK CAJAS ".$counter . " / ".$count_movs,
                    "last" => $last
                ];
            }else{
                return [
                    "msj" => "OK CAJAS ".$counter . " / ".$count_movs,
                    "last" => 0
                ];
            }
        
    }
    function getAuditoriaEfec(Request $req) {
        $qauditoriaefectivo = $req->qauditoriaefectivo;
        $id_sucursal = $req->sucursalqauditoriaefectivo;
        $fechasMain1 = $req->fechadesdeauditoriaefec;
        $fechasMain2 = $req->fechahastaauditoriaefec;
        $qcajaauditoriaefectivo = $req->qcajaauditoriaefectivo;
        

        $data = cajas::with(["sucursal","cat"])
        ->when($id_sucursal, function($q) use ($id_sucursal) {
            $q->where("id_sucursal",$id_sucursal);
        })
        ->when($qcajaauditoriaefectivo, function($q) use ($qcajaauditoriaefectivo) {
            $q->where("tipo",$qcajaauditoriaefectivo);
        })
        ->when($qauditoriaefectivo, function($q) use ($qauditoriaefectivo){
            $q->where("concepto","LIKE","%$qauditoriaefectivo%");
        })
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->orderBy("id_sucursal","asc")
        ->orderBy("idinsucursal","desc")
        ->get()
        ->map(function($q) use ($qcajaauditoriaefectivo)
        {
            $sumreal = cajas::where("id_sucursal",$q->id_sucursal)
            ->where("tipo",$qcajaauditoriaefectivo)
            ->where("idinsucursal","<=",$q->idinsucursal)
            ->orderBy("idinsucursal","desc");

            $lastcajacierre = cajas::where("id_sucursal",$q->id_sucursal)
            ->where("tipo",$qcajaauditoriaefectivo)
            ->where("concepto","INGRESO DESDE CIERRE")
            ->where("idinsucursal","<",$q->idinsucursal)
            ->orderBy("idinsucursal","desc")->first();
            $caja_incial_dolarbalance = 0;
            $caja_incial_bsbalance = 0;
            $caja_incial_pesobalance = 0;
            $caja_incial_eurobalance = 0;
            if ($lastcajacierre) {
                $caja_incial_dolarbalance = $lastcajacierre->dolarbalance;
                $caja_incial_bsbalance = $lastcajacierre->bsbalance;
                $caja_incial_pesobalance = $lastcajacierre->pesobalance;
                $caja_incial_eurobalance = $lastcajacierre->eurobalance;
            }
            $lastcierre = cierres::where("id_sucursal",$q->id_sucursal)->where("fecha","<",$q->fecha)->orderBy("fecha","desc")->first();
            $tasabs = 0;
            $tasacop = 0;
            $caja_incial_dejar_dolar = 0;
            $caja_incial_dejar_peso = 0;
            $caja_incial_dejar_bss = 0;
            
            if ($lastcierre) {
                $tasabs = $lastcierre->tasa;
                $tasacop = $lastcierre->tasacop;
                $caja_incial_dejar_dolar = $lastcierre->dejar_dolar;
                $caja_incial_dejar_peso = $lastcierre->dejar_peso;
                $caja_incial_dejar_bss = $lastcierre->dejar_bss;
                $ingreso_efectivo = $lastcierre->efectivo;
            }
            
            $ingreso_efectivo = 0;

            $tasabs_today = 0;
            $tasacop_today = 0;

            $today_dejar_dolar = 0;
            $today_dejar_peso = 0;
            $today_dejar_bss = 0;
            $todaycierre = cierres::where("id_sucursal",$q->id_sucursal)->where("fecha",$q->fecha)->first();
            if ($todaycierre) {
                $tasabs_today = $todaycierre->tasa;
                $tasacop_today = $todaycierre->tasacop;
                
                $ingreso_efectivo = $todaycierre->efectivo;
                $today_dejar_dolar = $todaycierre->dejar_dolar;
                $today_dejar_peso = $todaycierre->dejar_peso;
                $today_dejar_bss = $todaycierre->dejar_bss;
            }
            $total_dejar = $caja_incial_dejar_dolar + (new CierresController)->dividir($caja_incial_dejar_bss,$tasabs) + (new CierresController)->dividir($caja_incial_dejar_peso,$tasacop);
            $total_cajas = $caja_incial_dolarbalance + (new CierresController)->dividir($caja_incial_bsbalance, $tasabs) + (new CierresController)->dividir($caja_incial_pesobalance, $tasacop) + $caja_incial_eurobalance;
            $total_inicial =  $total_dejar + $total_cajas;
            
            $movdehoy = cajas::where("id_sucursal",$q->id_sucursal)
            ->where("tipo",$qcajaauditoriaefectivo)
            ->where("fecha",$q->fecha)
            
            ->where("concepto","<>","INGRESO DESDE CIERRE")->get();


            $movdehoypaarriba = cajas::where("id_sucursal",$q->id_sucursal)
            ->where("tipo",$qcajaauditoriaefectivo)
            ->where("idinsucursal",">",$q->idinsucursal)
            ->where("fecha",$q->fecha);

            $sumpaarriba_montodolar = $movdehoypaarriba->sum("montodolar");
            $sumpaarriba_montobs = $movdehoypaarriba->sum("montobs");
            $sumpaarriba_montopeso = $movdehoypaarriba->sum("montopeso");
            $sumpaarriba_montoeuro = $movdehoypaarriba->sum("montoeuro");

            $sumpaarriba = $sumpaarriba_montodolar + (new CierresController)->dividir($sumpaarriba_montobs,$tasabs) + (new CierresController)->dividir($sumpaarriba_montopeso,$tasacop) + $sumpaarriba_montoeuro;

            $adicionaleshoy_montodolar = $movdehoy->where("montodolar",">",0)->sum("montodolar");
            $egresoshoy_montodolar = $movdehoy->where("montodolar","<",0)->sum("montodolar");

            $adicionaleshoy_montobs = $movdehoy->where("montobs",">",0)->sum("montobs");
            $egresoshoy_montobs = $movdehoy->where("montobs","<",0)->sum("montobs");

            $adicionaleshoy_montopeso = $movdehoy->where("montopeso",">",0)->sum("montopeso");
            $egresoshoy_montopeso = $movdehoy->where("montopeso","<",0)->sum("montopeso");

            $adicionaleshoy_montoeuro = $movdehoy->where("montoeuro",">",0)->sum("montoeuro");
            $egresoshoy_montoeuro = $movdehoy->where("montoeuro","<",0)->sum("montoeuro");

            $adicionalesdehoy = $adicionaleshoy_montodolar+ (new CierresController)->dividir($adicionaleshoy_montobs,$tasabs_today) +(new CierresController)->dividir($adicionaleshoy_montopeso,$tasacop_today)+$adicionaleshoy_montoeuro;

            $egresosdehoy = $egresoshoy_montodolar+ (new CierresController)->dividir($egresoshoy_montobs,$tasabs_today) +(new CierresController)->dividir($egresoshoy_montopeso,$tasacop_today)+$egresoshoy_montoeuro;

            $dejehoy = $today_dejar_dolar + (new CierresController)->dividir($today_dejar_bss,$tasabs_today) + (new CierresController)->dividir($today_dejar_peso,$tasacop_today);
            
            $debestener = $total_inicial + (($ingreso_efectivo+$adicionalesdehoy)-$dejehoy) - abs($egresosdehoy) - $sumpaarriba;
            
            $sumasistema = $q->dolarbalance + (new CierresController)->dividir($q->bsbalance, $tasabs) + (new CierresController)->dividir($q->pesobalance, $tasacop) + $q->eurobalance;
            
            
            
            
            $q->dolarbalance_real = $sumreal->sum("montodolar");
            $q->bsbalance_real = $sumreal->sum("montobs");
            $q->pesobalance_real = $sumreal->sum("montopeso");
            $q->eurobalance_real = $sumreal->sum("montoeuro");
            $sumbruta = $sumreal->sum("montodolar") + (new CierresController)->dividir($sumreal->sum("montobs"), $tasabs) + (new CierresController)->dividir($sumreal->sum("montopeso"), $tasacop) + $sumreal->sum("montoeuro");
            
            $q->debestener = $debestener;
            $q->sumasistema = $sumasistema;
            $q->sumbruta = $sumbruta;
            $q->cuadre = ($sumbruta - $debestener) + ($sumbruta - $sumasistema);
            

            $q->total_inicial = $total_inicial;
            $q->total_dejar = $total_dejar;
            $q->total_cajas = $total_cajas;
            

            $q->ingreso_efectivo = $ingreso_efectivo;
            $q->adicionalesdehoy = $adicionalesdehoy;
            $q->dejehoy = $dejehoy;
            $q->egresosdehoy = $egresosdehoy;
            $q->egresoshoy_montodolar = $egresoshoy_montodolar;


            $q->caja_incial_dolarbalance = $caja_incial_dolarbalance;
            $q->caja_incial_bsbalance = (new CierresController)->dividir($caja_incial_bsbalance, $tasabs);
            $q->caja_incial_pesobalance = (new CierresController)->dividir($caja_incial_pesobalance, $tasacop);
            $q->caja_incial_eurobalance = $caja_incial_eurobalance;
            


            
            return $q;

        });

        return [
            "data" => $data,
            "sum" =>0
        ];
    }
    function getCajaMatriz(Request $req) {
        $qcajamatriz = $req->qcajamatriz;
        $sucursalqcajamatriz = $req->sucursalqcajamatriz;
        $fechadesdecajamatriz = $req->fechadesdecajamatriz;
        $fechahastacajamatriz = $req->fechahastacajamatriz;

        $data = cajas::with(["sucursal","cat"])
        ->when($qcajamatriz,function($q) use ($qcajamatriz) {
            $q->where("concepto","LIKE","%$qcajamatriz%");
        })
        ->when($sucursalqcajamatriz,function($q) use ($sucursalqcajamatriz) {
            $q->where("id_sucursal",$sucursalqcajamatriz);
        })
        ->when($fechadesdecajamatriz&&$fechahastacajamatriz,function($q) use ($fechadesdecajamatriz, $fechahastacajamatriz) {
            $q->whereBetween("fecha",[$fechadesdecajamatriz, $fechahastacajamatriz]);
        })
        ->whereIn("categoria",[42,40])
        //->orwhere("id_sucursal",13)
        ->orderBy("fecha","desc")
        ->get();
        
        $summatriz = cajas::whereIn("categoria",[42,40]);

        $bs = abs($summatriz->sum("montodolar"));
        $cop = abs($summatriz->sum("montobs"));
        $dolar = abs($summatriz->sum("montopeso"));
        $euro = abs($summatriz->sum("montoeuro"));

        

        return [
            "data" => $data,
            "balance" => [
                "bs" => $bs, 
                "cop" => $cop, 
                "dolar" => $dolar, 
                "euro" => $euro, 
            ]
            ];
    }
    function depositarmatrizalbanco(Request $req) {
        $id = $req->id;
        $fecha = $req->fecha;
        $bancoreq = $req->banco;

        $cm = cajas::find($id);
        $banco = bancos_list::find($bancoreq);
        $cat = catcajas::where("nombre","CAJA FUERTE: TRASPASO A CAJA CHICA")->first();
        $cierre = cierres::where("id_sucursal",$cm->id_sucursal)->where("fecha",$fecha)->first();

        if ($banco) {
            if ($cm) {
                if ($cm->montodolar) {
                    if ($cierre) {
                        $monto = abs($cm->montodolar);
                        $tasa = $cierre->tasa;
                        $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                            "loteserial" => $cm->concepto,
                            "banco" => $banco->codigo,
                            "categoria" => $cat->id,
                            "fecha" => $fecha,
                            "fecha_liquidacion" => $fecha,
                            "tipo" => "Transferencia",
                            "id_sucursal" => 13,
                            "id_beneficiario" => null,
                            "tasa" => 0,
                            "monto" => $monto*$tasa,
                            "monto_liquidado" => $monto*$tasa,
                            "monto_dolar" => 0,
                            "origen" => 2,
                            "id_usuario" => 1,
                        ]);
                    }
                }
                if ($cm->montobs!=0&&$cm->montobs!="0.00") {
                    $monto = abs($cm->montobs);
                    $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                        "loteserial" => $cm->concepto,
                        "banco" => $banco->codigo,
                        "categoria" => $cat->id,
                        "fecha" => $fecha,
                        "fecha_liquidacion" => $fecha,
                        "tipo" => "Transferencia",
                        "id_sucursal" => 13,
                        "id_beneficiario" => null,
                        "tasa" => 0,
                        "monto" => $monto,
                        "monto_liquidado" => $monto,
                        "monto_dolar" => 0,
                        "origen" => 2,
                        "id_usuario" => 1,
                    ]);
                }
                if ($cm->montopeso) {
                    
                }
                if ($cm->montoeuro) {
                    
                }
            }
        }

    }
}
