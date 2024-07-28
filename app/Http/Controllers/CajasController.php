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

    function setPagosCajas($data,$id_sucursal) {
        $bs = (new CierresController)->getTasa()["bs"];
        $cop = (new CierresController)->getTasa()["cop"];

        $catnombre = $data["cat"]["nombre"];
        $cattipo = $data["cat"]["tipo"];
        $catindice = $data["cat"]["id"];
        if (strpos($catnombre,"NOMINA QUINCENA")) {
            $split = explode("=",$data["concepto"]);
            if (isset($split[1])) {
                $ci = $split[1];
                $monto = $data["montodolar"];
                $monto += $data["montobs"]/$bs;
                $monto += $data["montopeso"]/$cop;
                $monto += $data["montoeuro"];
                return (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $data["id"], $data["fecha"]);
            }
        }
        if (strpos($catnombre,"NOMINA ABONO") || strpos($catnombre,"NOMINA PRESTAMO")) {
            $split = explode("=",$data["concepto"]);
            if (isset($split[1])) {
                $ci = $split[1];
                $monto = $data["montodolar"];
                $monto += round($data["montobs"]/$bs,2);
                $monto += round($data["montopeso"]/$cop,2);
                $monto += $data["montoeuro"];

                if (strpos($catnombre,"NOMINA ABONO")) {
                    $monto = abs($monto);
                }
                return (new NominaprestamosController)->setPrestamoNomina($ci, $monto, $id_sucursal, $data["id"],$data["fecha"]);
            }
        }
        if (strpos($catnombre,"PAGO PROVEEDOR")) {
            $split = explode("=",$data["concepto"]);
            if (isset($split[2])) {
                return $this->setCajaFun([
                    "id" => null,
                    "categoria" => $catindice,
                    "tipo" => 1,
                    "concepto" => $data["concepto"],
    
                    "montodolar" => abs($data["montodolar"]),
                    "montopeso" => abs($data["montopeso"]),
                    "montobs" => abs($data["montobs"]),
                    "montoeuro" => abs($data["montoeuro"]),
    
                    "fecha" => $data["fecha"],
                    "idinsucursal" => $data["id"],
                    "id_sucursal_origen" => $id_sucursal
                ]);
            }
        }
        if (strpos($catnombre,"TRASPASO A CAJA MATRIZ")) {
            return $this->setCajaFun([
                "id" => null,
                "categoria" => $catindice,
                "tipo" => 1,
                "concepto" => $data["concepto"],

                "montodolar" => abs($data["montodolar"]),
                "montopeso" => abs($data["montopeso"]),
                "montobs" => abs($data["montobs"]),
                "montoeuro" => abs($data["montoeuro"]),

                "fecha" => $data["fecha"],
                "idinsucursal" => $data["id"],
                "id_sucursal_origen" => $id_sucursal
            ]);
        }
    }
    function setEfecFromSucursalToCentral($movs, $id_sucursal) {

           /*  $bs = (new CierresController)->getTasa()["bs"];
            $cop = (new CierresController)->getTasa()["cop"]; */
        
            $count_movs = count($movs);
            $counter =0;
            $last = 0;
            if ($count_movs) {
                //return $movs;
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
                    

                    $this->setPagosCajas($e,$id_sucursal);

                    

                    $cc =  cajas::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $e["id"],
                    ],[
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
                    ]);
    
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
        ->when($qcajaauditoriaefectivo!="", function($q) use ($qcajaauditoriaefectivo) {
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
        $catDepositoAbanco = 65;
        $cierre = cierres::where("fecha",$fecha)->first();

        if ($banco) {
            if ($cm) {
                if (!$cm->id_sucursal_deposito) {

                    if ($cm->montodolar!=0&&$cm->montodolar!="0.00") {
                        if ($cierre) {
                            $monto = abs($cm->montodolar);
                            $tasa = $cierre->tasa;
                            $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                                "loteserial" => $cm->concepto,
                                "banco" => $banco->codigo,
                                "categoria" => $catDepositoAbanco,
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
                            $cm->id_sucursal_deposito = $p->id;
                            $cm->save();

                            $this->setCajaFun([
                                "id" => null,
                                "categoria" => $catDepositoAbanco,
                                "tipo" => 1,
                                "concepto" => $cm->concepto,
                
                                "montobs" => 0,
                                "montodolar" => $monto*-1,
                                "montopeso" => 0,
                                "montoeuro" => 0,
                
                                "fecha" => $fecha,
                                "id_sucursal_origen" => 13
                            ]);
                            return Response::json(["estado"=>true,"msj"=>"Éxito"]);


                        }else{
                            return Response::json(["estado"=>false,"msj"=>"No hay Tasa para la fecha!"]);

                        }
                    }

                    if ($cm->montobs!=0&&$cm->montobs!="0.00") {
                        $monto = abs($cm->montobs);
                        $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                            "loteserial" => $cm->concepto,
                            "banco" => $banco->codigo,
                            "categoria" => $catDepositoAbanco,
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
                        $cm->id_sucursal_deposito = $p->id;
                        $cm->save();

                        $this->setCajaFun([
                            "id" => null,
                            "categoria" => $catDepositoAbanco,
                            "tipo" => 1,
                            "concepto" => $cm->concepto,
            
                            "montobs" => $monto*-1,
                            "montodolar" => 0,
                            "montopeso" => 0,
                            "montoeuro" => 0,
            
                            "fecha" => $fecha,
                            "id_sucursal_origen" => 13
                        ]);
                        
                        return Response::json(["estado"=>true,"msj"=>"Éxito"]);
                    }
                }else{
                    return Response::json(["estado"=>false,"msj"=>"Ya fue liquidado!"]);
                }
            }
        }

    }



    //////////

    function ajustarbalancecajas($tipo) {
        $today = (new NominaController)->today();

        
        $inicial = cajas::where("tipo",$tipo)->where("id_sucursal",13)->orderBy("id","asc")->first();
        if ($inicial->count()==1) {
            $inicial = null;
        }
        //print_r($inicial);
        
        $inicial_dolarbalance = $inicial? $inicial->dolarbalance: 0;
        $inicial_bsbalance = $inicial? $inicial->bsbalance: 0;
        $inicial_pesobalance = $inicial? $inicial->pesobalance: 0;
        $inicial_eurobalance = $inicial? $inicial->eurobalance: 0;
        $ajustarlist = cajas::where("id",">",$inicial? $inicial->id: 0)->where("tipo",$tipo)->where("id_sucursal",13)->orderBy("id","asc")->get();
        
        $summontodolar = $inicial_dolarbalance;
        $summontobs = $inicial_bsbalance;
        $summontopeso = $inicial_pesobalance;
        $summontoeuro = $inicial_eurobalance;


        foreach ($ajustarlist as $i => $e) {
            $ajustar = cajas::find($e->id);
            
            $summontodolar += $e->montodolar;
            $summontobs += $e->montobs;
            $summontopeso += $e->montopeso;
            $summontoeuro += $e->montoeuro;

            if ($e->montodolar) {
                $ajustar->dolarbalance = $summontodolar;
            }
            if ($e->montobs) {
                $ajustar->bsbalance = $summontobs;
            }
            if ($e->montopeso) {
                $ajustar->pesobalance = $summontopeso;
            }
            if ($e->montoeuro) {
                $ajustar->eurobalance = $summontoeuro;
            }
            $ajustar->save();
        }
        return $inicial;

    }

    public function getControlEfec(Request $req) {
        $controlefecQ = $req->controlefecQ;
        $controlefecQDesde = $req->controlefecQDesde;
        $controlefecQHasta = $req->controlefecQHasta;
        $controlefecQCategoria = $req->controlefecQCategoria;

        $controlefecSelectGeneral = $req->controlefecSelectGeneral;

        $data = cajas::with(["cat","sucursal","sucursal_origen"])->where("tipo",$controlefecSelectGeneral)
        ->when($controlefecQ,function($q) use ($controlefecQ){
            $q->orWhere("concepto",$controlefecQ);
            $q->orWhere("monto",$controlefecQ);
        })
        ->when($controlefecQCategoria,function($q) use ($controlefecQCategoria) {
            $q->where("categoria",$controlefecQCategoria);
        })
        ->where("id_sucursal",13)
        ->whereBetween("fecha",[$controlefecQDesde,$controlefecQHasta])
        ->orderBy("id","desc")
        ->get();

        return Response::json([
            "data" => $data,
        ]);
    }

    function setCajaFun($arr) {
        $lastid = cajas::orderBy("id","desc")->first();
        
        $montodolar = isset($arr["montodolar"])?$arr["montodolar"]:0;
        $montopeso = isset($arr["montopeso"])?$arr["montopeso"]:0;
        $montobs = isset($arr["montobs"])?$arr["montobs"]:0;
        $montoeuro = isset($arr["montoeuro"])?$arr["montoeuro"]:0;
        $id_sucursal_origen = isset($arr["id_sucursal_origen"])?$arr["id_sucursal_origen"]:null;
        

        $idinsucursal = isset($arr["idinsucursal"])?$arr["idinsucursal"]:($lastid?$lastid->id + 1:1);
        $fecha = $arr["fecha"];

        $arr_insert = [
            "concepto" => $arr["concepto"],
            "categoria" => $arr["categoria"],
            "tipo" => $arr["tipo"],
            "fecha" => $fecha,

            "montodolar" => $montodolar,
            "montopeso" => $montopeso,
            "montobs" => $montobs,
            "montoeuro" => $montoeuro,
            
            "dolarbalance" => 0,
            "pesobalance" => 0,
            "bsbalance" => 0,
            "eurobalance" => 0,
            "estatus" => 1,
            "id_sucursal" => 13,
            "id_sucursal_origen" => $id_sucursal_origen,
            "idinsucursal" => $idinsucursal,
        ] ; 
        
        $arrbusqueda = ["id_sucursal"=>13, "idinsucursal"=>$idinsucursal];
        
        $cc =  cajas::updateOrCreate($arrbusqueda,$arr_insert);
        if ($cc) {
            $this->ajustarbalancecajas($arr["tipo"]);
            return $cc;
        }
    }
    function checkDelMovCajaFun($caja) {
        if ($caja->idincentralrecepcion) {
            $m = (new sendCentral)->checkDelMovCajaCentral($caja->idincentralrecepcion);
            if ($m===true) {
                return true;
            }else{
                return $m;
            }
           
        }else{
            return true;
        } 
    }
    function checkDelMovCaja($type,$val) {
        switch ($type) {
            case 'estatus':
                $c = cajas::where("estatus",$val)->get();
                foreach ($c as $i => $caja) {
                    if($caja->id_sucursal_destino || $caja->id_sucursal_emisora || $caja->idincentralrecepcion){
                        return $this->checkDelMovCajaFun($caja);
                    }
                }


                break;
            case 'id':
                $c = cajas::find($val);
                if($c->id_sucursal_destino || $c->id_sucursal_emisora || $c->idincentralrecepcion){
                    return $this->checkDelMovCajaFun($c);
                }
                break;
            
        }
        return true;
    }
    function reversarMovPendientes() {
        $check = $this->checkDelMovCaja("estatus",0); 
        if ($check===true) {
            cajas::where("estatus",0)->delete();
        }else{
            return $check;
        }
    }

    public function setControlEfec(Request $req) {
        $cat_efectivo_adicional= catcajas::orwhere("nombre","LIKE","%EFECTIVO ADICIONAL%")
        ->orwhere("nombre","LIKE","%NOMINA ABONO%")
        ->orwhere("nombre","LIKE","%INGRESO TRANSFERENCIA SUCURSAL%")
        ->orwhere("nombre","LIKE","%INGRESO TRANSFERENCIA TRABAJADOR%")
        ->orwhere("nombre","LIKE","%TRANSFERENCIA TRABAJADOR%")
        ->get("id")->map(function($q){return $q->id;})->toArray();
        
        $cat_tras_fuerte= catcajas::where("nombre","LIKE","%CAJA FUERTE: TRASPASO A CAJA CHICA%")->get("id")->map(function($q){return $q->id;})->toArray();
        $cat_tras_chica= catcajas::where("nombre","LIKE","%CAJA CHICA: TRASPASO A CAJA FUERTE%")->get("id")->map(function($q){return $q->id;})->toArray();
        
        $controlefecSelectGeneral = $req->controlefecSelectGeneral;
        $concepto = $req->concepto;
        $categoria = $req->categoria;
        $fecha = $req->fecha;
        
        $sendCentralData = $req->sendCentralData;
        $transferirpedidoa = $req->transferirpedidoa;
        
        $montodolar = 0;
        $montopeso = 0;
        $montobs = 0;
        $montoeuro = 0;
        
        $cat_trans_trabajador = catcajas::where("nombre","LIKE","%TRANSFERENCIA TRABAJADOR%")->first("id");
        if ($sendCentralData) {
            if ($categoria!=$cat_trans_trabajador->id) {
                return Response::json(["msj"=>"Error: Solo puede transferir TRANSFERENCIA TRABAJADOR","estado"=>false]);
            }
        }
        

        
        $factor = -1;
        if (in_array($categoria, $cat_efectivo_adicional)) {$factor = 1;}

        switch ($req->controlefecNewMontoMoneda) {
            case 'dolar':
                $montodolar = $req->monto*$factor;
            break;
            case 'peso':
                $montopeso = $req->monto*$factor;
            break;
            case 'bs':
                $montobs = $req->monto*$factor;
            break;
            case 'euro':
                $montoeuro = $req->monto*$factor;
            break;
        }
        $cajas = $this->setCajaFun([
            "id" => null,
            "concepto" => $concepto,
            "categoria" => $categoria,
            "montodolar" => $montodolar,
            "montopeso" => $montopeso,
            "montobs" => $montobs,
            "montoeuro" => $montoeuro,
            "tipo" => $controlefecSelectGeneral,
            "estatus" => ($controlefecSelectGeneral==0? 1: 0),
            "id_sucursal_destino" => $transferirpedidoa,
            "fecha" => $fecha,
            "ifforcentral" => ($controlefecSelectGeneral==1?$sendCentralData:false) 
        ]);
        $this->setPagosCajas(cajas::with("cat")->where("id",$cajas->id)->first(),13);

        if (in_array($categoria, $cat_tras_fuerte)) {
            $adicional= catcajas::where("nombre","LIKE","%EFECTIVO ADICIONAL%")->where("tipo",0)->first();
            $cajas = $this->setCajaFun([
                "id" => null,
                "concepto" => $concepto,
                "categoria" => $adicional->id,
                "montodolar" => $montodolar*-1,
                "montopeso" => $montopeso*-1,
                "montobs" => $montobs*-1,
                "montoeuro" => $montoeuro*-1,
                "tipo" => 0,
                "estatus" => 0,
                "fecha" => $fecha,

            ]);
        }
        if (in_array($categoria, $cat_tras_chica)) {
            
            $adicional= catcajas::orwhere("nombre","LIKE","%EFECTIVO ADICIONAL%")->where("tipo",1)->first();
            $cajas = $this->setCajaFun([
                "id" => null,
                "concepto" => $concepto,
                "categoria" => $adicional->id,
                "montodolar" => $montodolar*-1,
                "montopeso" => $montopeso*-1,
                "montobs" => $montobs*-1,
                "montoeuro" => $montoeuro*-1,
                "tipo" => 1,
                "estatus" => 0,
                "fecha" => $fecha,
            ]);
        }

        if ($cajas) {
            return Response::json(["msj"=>$cajas,"estado"=>true]);
        }
    
        return Response::json(["msj"=>"Error", "estado"=>false]);
    }
    function delCajaFun($id) {
        $check_last = cajas::orderBy("id","desc")->first("id");
        if ($check_last->id == $id) {
            
            $check_notingreso = cajas::find($id);
            if ($check_notingreso->tipo==1 && $check_notingreso->estatus==1) {
                return "No se puede eliminar movimiento aprobado";
            }

            if (str_contains($check_notingreso->concepto, 'GASTO CON MERCANCIA DE SUCURSAL PED') ) {
                return "No se puede eliminar gasto desde Pedido";
            }
            if ($check_notingreso->categoria != 1 && $check_notingreso->categoria != 2) {
                $check = $this->checkDelMovCaja("id",$id); 
                if ($check===true) {
                    cajas::find($id)->delete();
                }else{
                    return ($check);
                }
                echo "Exito";

            }else{
                return "Es un ingreso";
            }
        }else{
            return "No es el ultimo";
        }
    }

    function delCaja(Request $req) {
        return $this->delCajaFun($req->id);
    }
}
