<?php

namespace App\Http\Controllers;

use App\Models\bancos_list;
use App\Models\cajas;
use App\Models\catcajas;
use App\Models\cierres;
use App\Models\nomina;
use App\Models\puntosybiopagos;
use App\Models\sucursal;
use App\Http\Requests\StorepuntosybiopagosRequest;
use App\Http\Requests\UpdatepuntosybiopagosRequest;
use Illuminate\Http\Request;


class PuntosybiopagosController extends Controller
{
    function changeLiquidacionPagoElec(Request $req) {
        $id = $req->id;
        $change = puntosybiopagos::find($id);

        $change->fecha_liquidacion = date("Y-m-d");

        if ($change->save()) {
            return true;
        }

    }

    function reverserLiquidar(Request $req) {
        $id = $req->id;
        $p = puntosybiopagos::find($id);
        $p->fecha_liquidacion = null;
        $p->monto_liquidado = 0;
        $p->save() ;
    }

    function liquidarMov(Request $req) {
        $id = $req->id;
        $fecha = $req->fecha;
        $monto = $req->monto;
        $p = puntosybiopagos::find($id);
        $p->fecha_liquidacion = $fecha;
        $p->monto_liquidado = $monto;
        if ($p->save()) {
            return [
                "estado" => true,
                "msj" => "Éxito al Liquidar",
            ];
        }


    }
    
    function sendMovimientoBanco(Request $req) {
        try {
            $id = null;
            $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
            $cuentasPagosMonto = $req->cuentasPagosMonto;
            $cuentasPagosMetodo = $req->cuentasPagosMetodo;
            $cuentasPagosMetodoDestino = $req->cuentasPagosMetodoDestino;
            $cuentasPagosFecha = $req->cuentasPagosFecha;

            $iscomisiongasto = $req->iscomisiongasto; 
            $comisionpagomovilinterban = $req->comisionpagomovilinterban; 

            $catingresotras = catcajas::where("nombre","CAJA MATRIZ: INGRESO TRASPASO ENTRE CUENTAS")->first();
            $categresotras = catcajas::where("nombre","CAJA MATRIZ: EGRESO TRASPASO ENTRE CUENTAS")->first();
            $catcompg = catcajas::where("nombre","CAJA MATRIZ: COMISION TRANSFERENCIA INTERBANCARIA O PAGO MOVIL")->first();


            if ($catingresotras && $categresotras) {
                $today = new \DateTime((new NominaController)->today());
                $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
                    "nombre" => "ADMINISTRACION",
                    "codigo" => "administracion",
                ]);
                $banco = bancos_list::find($cuentasPagosMetodo);
                $bancoDestino = bancos_list::find($cuentasPagosMetodoDestino);

                $montopositivo = abs(floatval($cuentasPagosMonto));
                if ($banco) {
                    $mov1 = puntosybiopagos::updateOrCreate([
                        "id" => $id
                    ],[
                        "loteserial" => $cuentasPagosDescripcion,
                        "banco" => $bancoDestino->codigo,
                        "fecha" => $cuentasPagosFecha,
                        "monto" => $montopositivo,
                        "monto_liquidado" => $montopositivo,
    
                        "tipo" => "Transferencia",
                        "fecha_liquidacion" => $cuentasPagosFecha,
                        "id_usuario" => 1,
                        "id_sucursal" => $su->id,
                        "origen" => 2,
                        "categoria" => $catingresotras->id
                    ]);

                    $mov2 = puntosybiopagos::updateOrCreate([
                        "id" => $id
                    ],[
                        "loteserial" => $cuentasPagosDescripcion,
                        "banco" => $banco->codigo,
                        "fecha" => $cuentasPagosFecha,
                        "monto" => $montopositivo*-1,
                        "monto_liquidado" => $montopositivo*-1,
    
                        "tipo" => "Transferencia",
                        "fecha_liquidacion" => $cuentasPagosFecha,
                        "id_usuario" => 1,
                        "id_sucursal" => $su->id,
                        "origen" => 2,
                        "categoria" => $categresotras->id
                    ]);


                    $com = puntosybiopagos::updateOrCreate([
                        "id" => $id
                    ],[
                        "loteserial" => $cuentasPagosDescripcion." COMISION",
                        "banco" => $banco->codigo,
                        "fecha" => $cuentasPagosFecha,
                        "monto" => ($montopositivo*-1)*($comisionpagomovilinterban/100),
                        "monto_liquidado" => ($montopositivo*-1)*($comisionpagomovilinterban/100),
    
                        "tipo" => "Transferencia",
                        "fecha_liquidacion" => $cuentasPagosFecha,
                        "id_usuario" => 1,
                        "id_sucursal" => $su->id,
                        "origen" => 2,
                        "categoria" => $catcompg->id
                    ]);
            
                    if ($mov1) {
                        return [
                            "estado" => true,
                            "msj" => "Éxito"
                        ];
                    }
                }else{
                    return [
                        "estado" => false,
                        "msj" => "No se encontró banco seleccionado",
                    ];    
                }
            }
            
    
    
        } catch (\Exception $e) {
            return [
                "estado" => false,
                "msj" => $e->getMessage()
            ];
        }

    }

    function getGastosDistribucion(Request $req) {

        
        $gastosQFecha = $req->gastosQFecha;
        $gastosQFechaHasta = $req->gastosQFechaHasta;
        $gastosQsucursal = $req->gastosQsucursal;
        

        $gastosQ = "";
        $gastosQCategoria = "";
        $catgeneral = [2,3];
        $ingreso_egreso = "";
        $typecaja = "";
        $gastosorder = "desc";
        $gastosfieldorder = "montodolar";


        $pagoproveedor = (new CuentasporpagarController)->selectCuentaPorPagarProveedorDetallesFun([
            "fechasMain1" => $gastosQFecha,
            "fechasMain2" => $gastosQFechaHasta,

            "categoriacuentasPorPagarDetalles" => "",
            "cuentaporpagarAprobado" => 1,
            "id_facts_force" => null,
            "id_proveedor" => "",
            "numcuentasPorPagarDetalles" => "",
            "OrdercuentasPorPagarDetalles" => "desc",
            "qCampocuentasPorPagarDetalles" => "updated_at",
            "qcuentasPorPagarDetalles" => "",
            "qcuentasPorPagarTipoFact" => "abonos",
            "sucursalcuentasPorPagarDetalles" => $gastosQsucursal,
            "tipocuentasPorPagarDetalles" => "",
            "type" => "buscar",
        ]);

        $byproveedororden = [];
        $byproveedor = $pagoproveedor["detalles"]->groupBy(["id_proveedor"]);

        foreach ($byproveedor as $id_proveedor => $dataproveedors) {
            $descripcion = "";
            $rif = "";
            if ($dataproveedors->count()) {
                $descripcion = $dataproveedors[0]["proveedor"]["descripcion"];
                $rif = $dataproveedors[0]["proveedor"]["rif"];
            }
            array_push($byproveedororden, [
                "id_proveedor" => $id_proveedor,
                "sum" => $dataproveedors->sum("monto"),
                "descripcion" => $descripcion,
                "rif" => $rif,
                "data" => $dataproveedors,
            ]);
        }
        array_multisort(array_column($byproveedororden,"sum"),SORT_DESC,$byproveedororden);
        $pagoproveedor["byproveedor"] = $byproveedororden;
        //$pagoproveedor["bysucursal"] = $pagoproveedor["detalles"]->groupBy(["id_sucursal"]);

        $all = $this->getGastosFun([
            "gastosQ" => $gastosQ,
            "gastosQFecha" => $gastosQFecha,
            "gastosQFechaHasta" => $gastosQFechaHasta,
            "gastosQCategoria" => $gastosQCategoria,
            "catgeneral" => $catgeneral,
            "ingreso_egreso" => $ingreso_egreso,
            "typecaja" => $typecaja,
            "gastosorder" => $gastosorder,
            "gastosfieldorder" => $gastosfieldorder,
            "gastosQsucursal" => $gastosQsucursal,
        ]);

        $distribucionGastosCat = collect($all["data"])->groupBy("categoria");
        $distribucionGastosSucursal = collect($all["data"])->groupBy(["id_sucursal","categoria"]);

        $distribucionGastosCatMod = [];
        $distribucionGastosSucursalMod = [];
        
        foreach ($distribucionGastosCat as $i => $cat) {
            $sum = $cat->sum("montodolar");
            $bysucursales = $cat->groupBy("id_sucursal");
            $nombre = "";
            $id = "";
            $catgeneral = "";
            $ingreso_egreso = "";
            if ($cat->count()) {
                $nombre = $cat[0]["cat"]["nombre"];
                $id = $cat[0]["cat"]["id"];
                $catgeneral = $cat[0]["cat"]["catgeneral"];
                $ingreso_egreso = $cat[0]["cat"]["ingreso_egreso"];
            }
            if (!array_key_exists($catgeneral,$distribucionGastosCatMod)) {
                $distribucionGastosCatMod[$catgeneral] = [
                    "data"=>[],
                    "sum"=>0,
                ];
            }

            $bysucursalmod = [];

            foreach ($bysucursales as $id_bysucursal => $bysucursal) {
                array_push($bysucursalmod,[
                    "sum" => $bysucursal->sum("montodolar"),
                    "codigo_sucursal" => $bysucursal[0]["sucursal"]["codigo"],
                    "data" => $bysucursal,
                ]);
            }
            array_multisort(array_column($bysucursalmod, 'sum'), SORT_ASC, $bysucursalmod);

            array_push($distribucionGastosCatMod[$catgeneral]["data"],[
                "sum" => $sum,
                "nombre" => $nombre,
                "catgeneral" => $catgeneral,
                "ingreso_egreso" => $ingreso_egreso,
                "id" => $id,
                "por" => 0,
                "bysucursalmod" => $bysucursalmod,
            ]);
        }

        foreach ($distribucionGastosCatMod as $key => $q) {
            array_multisort(array_column($distribucionGastosCatMod[$key]["data"], 'sum'), SORT_ASC, $distribucionGastosCatMod[$key]["data"]);

        }
        foreach ($distribucionGastosSucursal as $id_sucursalkey => $cats_sucursal) {
            $sumsucursal = 0;
            $codigo_sucursal = "";
            $bycatMod = [];

            if (!array_key_exists($id_sucursalkey,$distribucionGastosSucursalMod)) {
                $distribucionGastosSucursalMod[$id_sucursalkey] = [
                    "data"=>[],
                    "sum"=>0,
                    "por"=>0,
                    "codigo_sucursal"=>"",
                ];
            }
            
            foreach ($cats_sucursal as $id_cat => $cats) {
                $sumsucursal += $cats->sum("montodolar");

                $bycats = $cats->groupBy(["categoria"]);
                
                $codigo_sucursal = $cats[0]["sucursal"]["codigo"];
                $nombre = $cats[0]["cat"]["nombre"];
                $id = $id_cat;
                $catgeneral = $cats[0]["cat"]["catgeneral"];
                $ingreso_egreso = $cats[0]["cat"]["ingreso_egreso"];
                if (!array_key_exists($id_cat,$distribucionGastosSucursalMod[$id_sucursalkey]["data"])) {
                    $distribucionGastosSucursalMod[$id_sucursalkey]["data"][$id_cat] = [
                        "data"=>[],
                        "detalles"=>$cats,
                        "sum"=>0,
                    ];
                }

                foreach ($bycats as $id_bycat => $bycat) {
                    array_push($bycatMod,[
                        "sum" => $bycat->sum("montodolar"),
                        "nombre" => $bycat[0]["cat"]["nombre"],
                        "id" => $bycat[0]["cat"]["id"],
                        "data" => $bycat,
                    ]);
                }
                
                array_push($distribucionGastosSucursalMod[$id_sucursalkey]["data"][$id_cat]["data"],[
                    "sum" => $cats->sum("montodolar"),
                    "nombre" => $nombre,
                    "catgeneral" => $catgeneral,
                    "ingreso_egreso" => $ingreso_egreso,
                    "id" => $id_cat,
                    "por" => 0,
                ]);
            }
            
            array_multisort(array_column($bycatMod, 'sum'), SORT_ASC, $bycatMod);
            $distribucionGastosSucursalMod[$id_sucursalkey]["bycatmod"] = $bycatMod;
            $distribucionGastosSucursalMod[$id_sucursalkey]["sum"] = $sumsucursal;
            $distribucionGastosSucursalMod[$id_sucursalkey]["codigo_sucursal"] = $codigo_sucursal;
            
        }

        foreach ($distribucionGastosCatMod as $key => $e) {
            $distribucionGastosCatMod[$key]["sum"] = array_sum(array_column($e["data"],"sum"));
        }
        /* foreach ($distribucionGastosSucursalMod as $key => $e) {
            $distribucionGastosSucursalMod[$key]["sum"] = array_sum(array_column($e["data"],"sum"));
        } */

        foreach ($distribucionGastosCatMod as $key => $q) {
            $sumCatMod = $q["sum"];
            foreach ($q["data"] as $keykey => $qq) {
                $distribucionGastosCatMod[$key]["data"][$keykey]["por"] = round(($sumCatMod==0||$qq["sum"]==0?0:  (abs($qq["sum"]*100)/$sumCatMod))  ,2);
            }
        }

        $sumTotalSucu = array_sum(array_column($distribucionGastosSucursalMod,"sum"));;
        foreach ($distribucionGastosSucursalMod as $key => $q) {
            $sumCatMod = $q["sum"];
            $distribucionGastosSucursalMod[$key]["por"] = round(($sumTotalSucu==0||$q["sum"]==0?0:  (abs($q["sum"]*100)/$sumTotalSucu)) ,2);
        }
        array_multisort(array_column($distribucionGastosSucursalMod, "sum"),SORT_ASC, $distribucionGastosSucursalMod);

        return [
            "distribucionGastosCat" => $distribucionGastosCatMod,
            "distribucionGastosSucursal" => $distribucionGastosSucursalMod,
            "pagoproveedor" => $pagoproveedor,
        ];
    }

    function autoliquidarTransferencia(Request $req) {
        $type = $req->type;
        $fechaAutoLiquidarTransferencia = $req->fechaAutoLiquidarTransferencia;
        $bancoAutoLiquidarTransferencia = $req->bancoAutoLiquidarTransferencia;

        $p = puntosybiopagos::whereBetween("fecha", [$fechaAutoLiquidarTransferencia, $fechaAutoLiquidarTransferencia])
        ->where("tipo","Transferencia")
        ->where("banco",$bancoAutoLiquidarTransferencia)
        ->get();
        if ($type=="auto") {
            foreach ($p as $i => $e) {
                $pp = puntosybiopagos::find($e->id);
                $pp->fecha_liquidacion = $pp->fecha;
                $pp->monto_liquidado = $pp->monto;
                $pp->save() ;
            }
        }else if ($type=="reversar"){
            foreach ($p as $i => $e) {
                $pp = puntosybiopagos::find($e->id);
                $pp->fecha_liquidacion = null;
                $pp->monto_liquidado = null;
                $pp->save() ;
            }
        }
    }

    function getGastosFun($arr) {

        $gastosQ = $arr["gastosQ"];
        $gastosQFecha = $arr["gastosQFecha"];
        $gastosQFechaHasta = $arr["gastosQFechaHasta"];
        $gastosQCategoria = $arr["gastosQCategoria"];
        $gastosQsucursal = isset($arr["gastosQsucursal"])?$arr["gastosQsucursal"]:"";
        
        $catgeneral = $arr["catgeneral"];
        $ingreso_egreso = $arr["ingreso_egreso"];
        $typecaja = $arr["typecaja"];
        $gastosorder = $arr["gastosorder"];
        $gastosfieldorder = $arr["gastosfieldorder"];


        
        
        $gastos =  cajas::with(["sucursal","cat"])
        ->when($gastosQ,function($q) use ($gastosQ){
            $q->where("concepto","LIKE","%$gastosQ%");
        })
        ->when($gastosQFecha,function($q) use ($gastosQFecha,$gastosQFechaHasta) {
            $q->whereBetween("fecha", [$gastosQFecha, !$gastosQFechaHasta?$gastosQFecha:$gastosQFechaHasta]);
        })
        ->when($gastosQCategoria,function($q) use ($gastosQCategoria) {
            $q->where("categoria",$gastosQCategoria);
        })
        ->when($gastosQsucursal,function($q) use ($gastosQsucursal) {
            $q->where("id_sucursal",$gastosQsucursal);
        })
        ->when($typecaja,function($q) use ($typecaja) {
            $q->where("tipo",$typecaja);
        })
        ->when($catgeneral,function($q) use ($catgeneral) {
            $q->whereIn("categoria",catcajas::whereIn("catgeneral",$catgeneral)->select("id"));
        })
        ->when($ingreso_egreso,function($q) use ($ingreso_egreso) {
            $q->whereIn("categoria",catcajas::where("ingreso_egreso",$ingreso_egreso)->select("id"));
        })
        ->get()
        ->map(function($q) {
            $c = cierres::where("fecha",$q->fecha)->where("id_sucursal",$q->id_sucursal)->first();
            if($c){
                $bs = $c->tasa;
                $cop = $c->tasacop;
    
                $q->ingreso_egreso = $q->cat->ingreso_egreso;
                $q->catgeneral = $q->cat->catgeneral;
                $q->variable_fijo = $q->cat->variable_fijo;
    
                $montodolar = ($q->montodolar) + ($q->montobs/$bs) + ($q->montopeso/$cop);
                $q->montodolar = $montodolar; 
                
                $q->pago_efectivo = $montodolar;
                $q->pago_banco = 0;

            }else{
                $bs =1;
                $cop = 1;
    
                $q->ingreso_egreso = $q->cat->ingreso_egreso;
                $q->catgeneral = $q->cat->catgeneral;
                $q->variable_fijo = $q->cat->variable_fijo;
    
                $montodolar = ($q->montodolar) + ($q->montobs/$bs) + ($q->montopeso/$cop);
                $q->montodolar = $montodolar; 
                
                $q->pago_efectivo = $montodolar;
                $q->pago_banco = 0;
            }


            return $q;
        });

        $p =  puntosybiopagos::with(["sucursal","beneficiario","cat"])
        ->where("origen", 2)
        ->when($gastosQ,function($q) use ($gastosQ){
            $q->where(function($q) use ($gastosQ) {
                $q->orwhere("loteserial","LIKE","%$gastosQ%")
                ->orwhere("banco","LIKE","%$gastosQ%");
            });
        })
        ->when($gastosQsucursal,function($q) use ($gastosQsucursal) {
            $q->where("id_sucursal",$gastosQsucursal);
        })
        ->when($gastosQFecha,function($q) use ($gastosQFecha,$gastosQFechaHasta) {
            $q->whereBetween("fecha_liquidacion", [$gastosQFecha, !$gastosQFechaHasta?$gastosQFecha:$gastosQFechaHasta]);
        })
        ->when($gastosQCategoria,function($q) use ($gastosQCategoria) {
            $q->where("categoria",$gastosQCategoria);
        })
        ->get()
        ->map(function($q) {
            $tasa = $q->tasa?abs($q->tasa):0;
            $monto_liquidado = $q->monto_liquidado?$q->monto_liquidado:0;
            $monto_dolar = $q->monto_dolar?$q->monto_dolar:0;
            
            $bs = 0;
            if ($tasa!=0&&$monto_liquidado!=0) {
                $bs += $monto_liquidado/($tasa);
            }
            $q->bs = $bs;
            $q->sum = $monto_dolar+$bs;
            
            $q->montodolar = $monto_dolar+$bs;
            $q->ingreso_egreso = $q->cat->ingreso_egreso;
            $q->catgeneral = $q->cat->catgeneral;
            $q->variable_fijo = $q->cat->variable_fijo;

            $q->pago_efectivo = 0;
            $q->pago_banco = $monto_dolar+$bs;

            return $q;  
        });

        $alldata = array_merge($gastos->toArray(), $p->toArray());
        array_multisort(array_column($alldata, $gastosfieldorder), $gastosorder=="desc"? SORT_DESC: SORT_ASC, $alldata);

        return [
            "data" => $alldata,
            "sum" => array_sum(array_column($alldata,"montodolar"))
        ];
    }

    function getGastos(Request $req) {
        $gastosQ = $req->gastosQ;
        $gastosQFecha = $req->gastosQFecha;
        $gastosQFechaHasta = $req->gastosQFechaHasta;
        $gastosQCategoria = $req->gastosQCategoria;
        $gastosQsucursal = $req->gastosQsucursal;
        

        $catgeneral = $req->catgeneral?[$req->catgeneral]:"";
        $ingreso_egreso = $req->ingreso_egreso;
        $typecaja = $req->typecaja;

        $gastosorder = $req->gastosorder;
        $gastosfieldorder = $req->gastosfieldorder;

        
        $alldata = $this->getGastosFun([
            "gastosQ" => $gastosQ,
            "gastosQFecha" => $gastosQFecha,
            "gastosQFechaHasta" => $gastosQFechaHasta,
            "gastosQCategoria" => $gastosQCategoria,
            "gastosQsucursal" => $gastosQsucursal,
            "catgeneral" => $catgeneral,
            "ingreso_egreso" => $ingreso_egreso,
            "typecaja" => $typecaja,
            "gastosorder" => $gastosorder,
            "gastosfieldorder" => $gastosfieldorder,
        ]);
        
        return [
            "data" => $alldata["data"],
            "sum" => $alldata["sum"],
        ];
    }
    function changeBank(Request $req) {
        $type = $req->type;
        $upd = puntosybiopagos::find($req->id);

        switch ($type) {
            case 'banco':
                $upd->banco = $req->banco;
            break;
            case 'debito_credito':
                $upd->debito_credito = $req->banco;
            break;
            case 'monto':
                $upd->monto = $req->banco;
            break;
        }
        $upd->save();
        
    }
    function delGasto(Request $req) {
        $id = $req->id;

        $del = puntosybiopagos::where("id", $id)->delete();
        if ($del) {
            return [
                "estado"=> true,
                "msj"=> "Éxito al eliminar ".$id,
            ];
        }
    }
    function saveNewGasto(Request $req) {
        $gastosDescripcion = $req->gastosDescripcion;
        $gastosCategoria = $req->gastosCategoria;
        $gastosFecha = $req->gastosFecha;
        $gastosBanco = $req->gastosBanco;
        
        $gastosMonto = $req->gastosMonto;
        $gastosMonto_dolar = $req->gastosMonto_dolar;
        $gastosTasa = $req->gastosTasa;

        $gastosBeneficiario = $req->gastosBeneficiario;
        $modeEjecutor = $req->modeEjecutor;
        $listBeneficiario = $req->listBeneficiario;

        $iscomisiongasto = $req->iscomisiongasto;
        $comisionpagomovilinterban = $req->comisionpagomovilinterban;

        $catcompg = catcajas::where("nombre","CAJA MATRIZ: COMISION TRANSFERENCIA INTERBANCARIA O PAGO MOVIL")->first();

        
        $montoDolar = 0;
        $montoBs = 0;
        $taseBs = 0;
        $modeMoneda = $req->modeMoneda;
        if ($modeMoneda=="dolar") {
            $montoDolar = abs($gastosMonto_dolar)*-1;
        }elseif ($modeMoneda=="bs"){
            $montoBs = abs(floatval($gastosMonto))*-1;
            $taseBs = abs(floatval($gastosTasa));
        }
        $tipo = "Transferencia";
        if (strtoupper($gastosBanco)=="EFECTIVO") {
            $tipo = "EFECTIVO";
        }
        $admin_id = 13;

        $arrForce = [];
        if (!count($listBeneficiario)) {
            array_push($arrForce, $gastosBeneficiario);
        }else{
            $arrForce = $listBeneficiario;
        }
        $arr = [];
        $divisor = count($arrForce);
        foreach ($arrForce as $id) {
            $id_sucursal = null;
            $id_beneficiario = null;
            $id_selectEjecutor = $id["id"];
            
            if ($modeEjecutor=="personal") {
                $id_sucursal = $admin_id;
                $id_beneficiario = $id_selectEjecutor;
                
            }else if ($modeEjecutor== "sucursal") {
                $id_sucursal = $id_selectEjecutor;
                $id_beneficiario = null;
            }
            array_push($arr, [
                "id_sucursal" => $id_sucursal,
                "id_beneficiario"=>$id_beneficiario,
                "tasa" => $taseBs,
                "monto_dolar" => $montoDolar? ($montoDolar/$divisor): 0,
                "monto" => $montoBs? ($montoBs/$divisor): 0,

            ]);
        }
        
        $selectIdGastos = $req->selectIdGastos;
        $num = 0;
        foreach ($arr as $e) {
            $p = puntosybiopagos::updateOrCreate(["id"=>$selectIdGastos],[
                "loteserial" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):""),
                "banco" => $gastosBanco,
                "categoria" => $gastosCategoria,
                "fecha" => $gastosFecha,
                "fecha_liquidacion" => $gastosFecha,
                "tipo" => $tipo,

                "id_sucursal" => $e["id_sucursal"],
                "id_beneficiario" => $e["id_beneficiario"],
                "tasa" => $e["tasa"],
                
                "monto" => $e["monto"],
                "monto_liquidado" => $e["monto"],
                "monto_dolar" => $e["monto_dolar"],

                "origen" => 2,
                "id_usuario" => 1,
            ]);
            if ($p) {
                $num++;

                if ($iscomisiongasto==1) {
                    puntosybiopagos::updateOrCreate(["id"=>$selectIdGastos],[
                        "loteserial" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):"")." COMISION",
                        "banco" => $gastosBanco,
                        "categoria" => $catcompg->id,
                        "fecha" => $gastosFecha,
                        "fecha_liquidacion" => $gastosFecha,
                        "tipo" => $tipo,
        
                        "id_sucursal" => $e["id_sucursal"],
                        "id_beneficiario" => $e["id_beneficiario"],
                        "tasa" => $e["tasa"],
                        
                        "monto" => $e["monto"]*($comisionpagomovilinterban/100),
                        "monto_liquidado" => $e["monto"]*($comisionpagomovilinterban/100),
                        "monto_dolar" => $e["monto_dolar"]*($comisionpagomovilinterban/100),
        
                        "origen" => 2,
                        "id_usuario" => 1,
                    ]);
                }
                if ($e["id_beneficiario"]) {
                    $personal = nomina::find($id_beneficiario);
                    $catcajas = catcajas::find($gastosCategoria);
                    $catnombre = $catcajas->nombre;
                    $ci = $personal->nominacedula;
                    $monto = $montoDolar? ($montoDolar/$divisor): (($montoBs/$taseBs)/$divisor);

                    if (strpos($catnombre,"NOMINA QUINCENA")) {
                        (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $p->id, $gastosFecha);
                    }
                    if (strpos($catnombre,"NOMINA ABONO") || strpos($catnombre,"NOMINA PRESTAMO")) {
                        if (strpos($catnombre,"NOMINA ABONO")) {
                            $monto = abs($monto);
                        }
                        (new NominaprestamosController)->setPrestamoNomina($ci, $monto, $id_sucursal, $p->id, $gastosFecha);
                    }
                    
                    //(new NominapagosController)->setPagoNomina($personal->nominacedula, , $e["id_sucursal"], $p->id, $gastosFecha);
                }
            }
        }
        return [
            "msj" => $num." movimiento".($num<=1?"":"s")." cargado".($num<=1?"":"s"),
            "estado" => true,
        ];
    }

    function getMovBancos(Request $req) {
        $controlbancoQ = $req->controlbancoQ;
        $controlbancoQCategoria = $req->controlbancoQCategoria;
        $controlbancoQDesde = $req->controlbancoQDesde;
        $controlbancoQHasta = $req->controlbancoQHasta;
        $controlbancoQBanco = $req->controlbancoQBanco;
        $controlbancoQSiliquidado = $req->controlbancoQSiliquidado;

        $controlbancoQSucursal = $req->controlbancoQSucursal;

        $data = puntosybiopagos::with([
            "sucursal",
            "beneficiario",
            "cat",
        ])
        ->when($controlbancoQ,function($q) use ($controlbancoQ) {
            $q->orwhere("loteserial","LIKE","%$controlbancoQ%")
            ->orwhere("monto_liquidado",$controlbancoQ)
            ->orwhere("monto",$controlbancoQ);
        })
        ->when($controlbancoQCategoria,function($q) use ($controlbancoQCategoria) {
            $q->where("categoria",$controlbancoQCategoria);
        })
        ->when($controlbancoQSucursal,function($q) use ($controlbancoQSucursal) {
            $q->where("id_sucursal",$controlbancoQSucursal);
        })
        ->when($controlbancoQBanco,function($q) use ($controlbancoQBanco) {
            $q->where("banco",$controlbancoQBanco);
        })
        ->when($controlbancoQDesde && $controlbancoQHasta,function($q) use ($controlbancoQDesde,$controlbancoQHasta) {
            $q->whereBetween("fecha_liquidacion",[$controlbancoQDesde,$controlbancoQHasta]);
        })
        ->orderBy("updated_at","asc")
        ->get();

        return [
            "data" => $data
        ];
    }
}
