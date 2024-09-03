<?php

namespace App\Http\Controllers;

set_time_limit(60000);

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
use Response;


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

        $delcom = puntosybiopagos::find($p->id_comision);
        if ($delcom) {
            $delcom->delete();
        }

        $p->save() ;
    }
    function reportarMov(Request $req) {
        $id = $req->id;
        $monto = $req->inpmontoNoreportado;
        $fecha = $req->inpfechaNoreportado;

        
        $p = puntosybiopagos::find($id);
        $p->fecha = $fecha;
        $p->monto = $monto;

        if ($p->save()) {
            return [
                "estado" => true,
                "msj" => "Éxito al Liquidar",
            ];
        }
            
    }

    function liquidarMov(Request $req) { 
        $id = $req->id;
        $fecha = $req->fecha;
        $monto = $req->monto;
        $p = puntosybiopagos::find($id);
        $p->fecha_liquidacion = $fecha;
        $p->monto_liquidado = $monto;
        if ($p->save()) {
            $comision = $p->monto - $monto;
            if ($comision > 0 && $p->tipo != "Transferencia") {
                $liquidado = puntosybiopagos::find($id);
                $catcompos = catcajas::where("nombre","CAJA MATRIZ: COMISION PUNTO DE VENTA")->first();
                $comision_monto = abs($comision)*-1;
                $com = puntosybiopagos::updateOrCreate([
                    "id_origen_comision" => $id
                ],[
                    "loteserial" => $liquidado->loteserial." COMISION POS",
                    "banco" => $liquidado->banco,
                    "id_banco" => $liquidado->id_banco,
                    "fecha" => $liquidado->fecha,
                    "fecha_liquidacion" => $liquidado->fecha_liquidacion,
                    "monto" => $comision_monto,
                    "monto_liquidado" => $comision_monto,
                    
                    "tipo" => "Transferencia",
                    "debito_credito" => $liquidado->debito_credito,
                    "id_usuario" => $liquidado->id_usuario,
                    "id_sucursal" => $liquidado->id_sucursal,
                    "origen" => $liquidado->origen,

                    "categoria" => $catcompos->id
                ]);
                $liquidado->id_comision = $com->id;
                $liquidado->save();
            }
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
                $admin_id = 13;
                $banco = bancos_list::find($cuentasPagosMetodo);
                $bancoDestino = bancos_list::find($cuentasPagosMetodoDestino);

                $montopositivo = abs(floatval($cuentasPagosMonto));
                if ($banco) {
                    $mov1 = puntosybiopagos::updateOrCreate([
                        "id" => $id
                    ],[
                        "loteserial" => $cuentasPagosDescripcion,
                        "banco" => $bancoDestino->codigo,
                        "id_banco" => $bancoDestino->id,
                        "fecha" => $cuentasPagosFecha,
                        "monto" => $montopositivo,
                        "monto_liquidado" => $montopositivo,
    
                        "tipo" => "Transferencia",
                        "fecha_liquidacion" => $cuentasPagosFecha,
                        "id_usuario" => session("id_usuario"),
                        "id_sucursal" => $admin_id,
                        "origen" => 2,
                        "categoria" => $catingresotras->id
                    ]);

                    $mov2 = puntosybiopagos::updateOrCreate([
                        "id" => $id
                    ],[
                        "loteserial" => $cuentasPagosDescripcion,
                        "banco" => $banco->codigo,
                        "id_banco" => $banco->id,
                        "fecha" => $cuentasPagosFecha,
                        "monto" => $montopositivo*-1,
                        "monto_liquidado" => $montopositivo*-1,
    
                        "tipo" => "Transferencia",
                        "fecha_liquidacion" => $cuentasPagosFecha,
                        "id_usuario" => session("id_usuario"),
                        "id_sucursal" => $admin_id,
                        "origen" => 2,
                        "categoria" => $categresotras->id
                    ]);

                    if ($iscomisiongasto) {
                        $com = puntosybiopagos::updateOrCreate([
                            "id" => $id
                        ],[
                            "loteserial" => $cuentasPagosDescripcion." COMISION",
                            "banco" => $banco->codigo,
                            "id_banco" => $banco->id,
                            "fecha" => $cuentasPagosFecha,
                            "monto" => ($montopositivo*-1)*($comisionpagomovilinterban/100),
                            "monto_liquidado" => ($montopositivo*-1)*($comisionpagomovilinterban/100),
        
                            "tipo" => "Transferencia",
                            "fecha_liquidacion" => $cuentasPagosFecha,
                            "id_usuario" => session("id_usuario"),
                            "id_sucursal" => $admin_id,
                            "origen" => 2,
                            "categoria" => $catcompg->id
                        ]);
                    }
            
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

        $fil = array_filter($all["data"],function($q) {
            return $q["cat"]["catgeneral"]==2||$q["cat"]["catgeneral"]==3;
        });

        $distribucionGastosCat = collect($fil)->groupBy("categoria");
        $distribucionGastosSucursal = collect($fil)->groupBy(["id_sucursal","categoria"]);

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
                $tipo = $cat[0]["cat"]["tipo"];
                $catgeneral = $cat[0]["cat"]["catgeneral"];
                $ingreso_egreso = $cat[0]["cat"]["ingreso_egreso"];
            }
            $catgeneral = ($catgeneral==3||$catgeneral==2)?($catgeneral.$tipo):$catgeneral;
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

    function saveNewmovnoreportado(Request $req) {
        try {
            $newmovnoreportadomonto = $req->newmovnoreportadomonto;
            $newmovnoreportadobanco = $req->newmovnoreportadobanco;
            $newmovnoreportadofecha = $req->newmovnoreportadofecha;
            $newmovnoreportadoref = $req->newmovnoreportadoref;
    
            $id_cat_noportada = 66;

            $banco_codigo = bancos_list::find($newmovnoreportadobanco)->codigo;

            $newmovnoreportado = puntosybiopagos::updateOrCreate(["id"=>null],[
                "loteserial" => $newmovnoreportadoref." NO REPORTADA",
                "banco" => $banco_codigo,
                "id_banco" => $newmovnoreportadobanco,
                "categoria" => $id_cat_noportada,
                "fecha" => null,
                "fecha_liquidacion" => $newmovnoreportadofecha,
                "tipo" => "Transferencia",
    
                "id_sucursal" => 13,
                "id_beneficiario" => null,
                "tasa" => null,
                
                "monto" => null,
                "monto_liquidado" => $newmovnoreportadomonto,
                "monto_dolar" => null,
    
                "origen" => 2,
                "id_usuario" => session("id_usuario"),
            ]);
    
            if ($newmovnoreportado) {
                return Response::json(["estado" => true,"msj"=>"Éxito"]);
            }
        } catch (\Exception $e) {
            return Response::json(["estado" => false,"msj"=>"Error: ".$e->getMessage()]);
        }
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


        
        
        $gastos =  cajas::with(["sucursal","cat","proveedor","beneficiario"])
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
            $c = cierres::where("fecha","<=",$q->fecha)->orderBy("fecha","desc")->first();
            if($c){
                $bs = $c->tasa;
                $cop = $c->tasacop;
            }else{
                $bs = 1;
                $cop = 1;
            }

            $q->ingreso_egreso = $q->cat->ingreso_egreso;
            $q->catgeneral = $q->cat->catgeneral;
            $q->variable_fijo = $q->cat->variable_fijo;

            $montodolar = ($q->montodolar) + (new CierresController)->dividir($q->montobs,$bs) + (new CierresController)->dividir($q->montopeso,$cop);
            $q->montodolar = $montodolar; 
            
            $q->pago_efectivo = $montodolar;
            $q->pago_banco = 0;

            return $q;
        });

        $p =  puntosybiopagos::with(["sucursal","beneficiario","cat","usuario"])
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
            $b = bancos_list::find($q->id_banco);
            if ($b) {
                if ($b->moneda=="dolar") {
                    $tasa = 1;
                }
            }
            
            $bs = (new CierresController)->dividir($monto_liquidado,$tasa);
            
            $q->bs = $bs;
            $q->sum = $monto_dolar+$bs;

            if ($q->id_beneficiario) {
                $q->id_sucursal = $q->beneficiario->nominasucursal;
            }
            
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
                $id_banco = bancos_list::where("codigo",$req->banco)->first()->id;
                $upd->banco = $req->banco;
                $upd->id_banco = $id_banco;
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
        $controlefecNewMontoMoneda = $req->controlefecNewMontoMoneda;
        $gastosBancoDivisaDestino = $req->gastosBancoDivisaDestino;

        if ($gastosCategoria==64 && !$gastosBancoDivisaDestino) {
            return [
                "msj" => "SELECCIONE BANCO DESTINO PARA DIVISA",
                "estado" => false,
            ];
        }

        if (!$gastosMonto || !$gastosDescripcion || !$gastosFecha || !$gastosCategoria) {
            return [
                "msj" => "CAMPOS VACÍOS!",
                "estado" => false,
            ];
        }
        

        $catcompg = catcajas::where("nombre","CAJA MATRIZ: COMISION TRANSFERENCIA INTERBANCARIA O PAGO MOVIL")->first();

        $factor = 1;
        if ($gastosCategoria==1||$gastosCategoria==27) {
            $factor = -1;
        }
        if ($gastosCategoria==66) {
            return [
                "msj" => "CATEGORÍA NO VÁLIDA",
                "estado" => false,
            ];
        }

        
        $montoDolar = 0;
        $montoBs = 0;
        $taseBs = 0;
        $modeMoneda = $req->modeMoneda;
        if ($modeMoneda=="dolar") {
            $montoDolar = abs($gastosMonto_dolar)*-1*$factor;
        }elseif ($modeMoneda=="bs"){
            $montoBs = abs(floatval($gastosMonto))*-1*$factor;
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

        if ($gastosBanco==="EFECTIVO") {
            
            foreach ($arr as $e) {
                

                $montodolar = 0;
                $montopeso = 0;
                $montobs = 0;
                $montoeuro = 0;
                switch ($controlefecNewMontoMoneda) {
                    case 'dolar':
                        $montodolar = $e["monto"]*$factor;
                    break;
                    case 'peso':
                        $montopeso = $e["monto"]*$factor;
                    break;
                    case 'bs':
                        $montobs = $e["monto"]*$factor;
                    break;
                    case 'euro':
                        $montoeuro = $e["monto"]*$factor;
                    break;
                }


                $cajas = (new CajasController)->setCajaFun([
                    "id" => null,
                    "concepto" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):""),
                    "categoria" => $gastosCategoria,
                    "fecha" => $gastosFecha,

                    "montodolar" => $montodolar,
                    "montopeso" => $montopeso,
                    "montobs" => $montobs,
                    "montoeuro" => $montoeuro,

                    "tipo" => 1,
                    "estatus" => 1,
                    "id_sucursal_destino" => $e["id_sucursal"],
                    "id_sucursal" => $e["id_sucursal"],

                    "id_beneficiario" => $e["id_beneficiario"],
                    "origen" => 2,

                ]);
               
                if ($cajas) {
                    $num++;
    
                    if ($e["id_beneficiario"]) {
                        $personal = nomina::find($id_beneficiario);
                        $catcajas = catcajas::find($gastosCategoria);
                        $catnombre = $catcajas->nombre;
                        $ci = $personal->nominacedula;
                        $monto = $montoDolar? ($montoDolar/$divisor): (($montoBs/$taseBs)/$divisor);
    
                        if (strpos($catnombre,"NOMINA QUINCENA")) {
                            (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $cajas->id, $gastosFecha);
                        }
                        if (strpos($catnombre,"NOMINA ABONO") || strpos($catnombre,"NOMINA PRESTAMO")) {
                            if (strpos($catnombre,"NOMINA ABONO")) {
                                $monto = abs($monto);
                            }
                            (new NominaprestamosController)->setPrestamoNomina($ci, $monto, $id_sucursal, $cajas->id, $gastosFecha);
                        }
                        
                        //(new NominapagosController)->setPagoNomina($personal->nominacedula, , $e["id_sucursal"], $cajas->id, $gastosFecha);
                    }
                }
            }
        }else{
            $banco_codigo = bancos_list::find($gastosBanco)->codigo;
            foreach ($arr as $e) {
                $p = puntosybiopagos::updateOrCreate(["id"=>$selectIdGastos],[
                    "loteserial" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):""),
                    "banco" => $banco_codigo,
                    "id_banco" => $gastosBanco,
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
                    "id_usuario" => session("id_usuario"),
                ]);
                if ($p) {
                    $num++;

                    if ($gastosCategoria==64) {
                        $bancos_list = bancos_list::find($gastosBancoDivisaDestino);
                        $montodivisa =  abs((new CierresController)->dividir($e["monto"],$e["tasa"]));

                        $p = puntosybiopagos::updateOrCreate(["id"=>$selectIdGastos],[
                            "loteserial" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):""),
                            "banco" => $bancos_list->codigo,
                            "id_banco" => $gastosBancoDivisaDestino,

                            "categoria" => $gastosCategoria,
                            "fecha" => $gastosFecha,
                            "fecha_liquidacion" => $gastosFecha,
                            "tipo" => $tipo,
            
                            "id_sucursal" => $e["id_sucursal"],
                            "id_beneficiario" => $e["id_beneficiario"],
                            "tasa" => $e["tasa"],
                            
                            "monto" => ($montodivisa),
                            "monto_liquidado" => ($montodivisa),
                            "monto_dolar" => $e["monto_dolar"],
            
                            "origen" => 2,
                            "id_usuario" => session("id_usuario"),
                        ]);
                    }
    
                    if ($iscomisiongasto==1) {
                        puntosybiopagos::updateOrCreate(["id"=>$selectIdGastos],[
                            "loteserial" => $gastosDescripcion.($divisor>1?(" 1/".$divisor):"")." COMISION",
                            "banco" => $banco_codigo,
                            "id_banco" => $gastosBanco,
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
                            "id_usuario" => session("id_usuario"),
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
        ->orderBy("updated_at","desc")
        ->get();

        return [
            "data" => $data
        ];
    }

    function setmovsjunio() {
        $arr = [
            ["achaguas","3","2024-06-27","0134","5","0901 (DEBITO)","20860.11","PUNTO"],
                ["achaguas","3","2024-06-27","0134","5","0901 (CREDITO)","252.00","PUNTO"],
                ["achaguas","3","2024-06-27","0108","3","178 (DEBITO)","16685.13","PUNTO"],
                ["achaguas","3","2024-06-27","0134","5","000217 (DEBITO)","24097.90","PUNTO"],
                ["achaguas","3","2024-06-27","0108","3","04169940523","19936.24","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04161525735","138.24","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04161525735","105.50","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04140503882","701.94","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04268283979","50.93","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04243475101","27.29","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04166426238","2510.22","Transferencia"],
                ["achaguas","3","2024-06-27","0102","2","56793352","336285.08","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04262923507","4367.06","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04260357495","32.74","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04144439486","465.66","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04243145059","465.66","Transferencia"],
                ["achaguas","3","2024-06-27","0108","3","04124239780","72.76","Transferencia"],
                ["achaguas","3","2024-06-28","0134","5","000218 (DEBITO)","41611.97","PUNTO"],
                ["achaguas","3","2024-06-28","0134","5","000218 (CREDITO)","328.28","PUNTO"],
                ["achaguas","3","2024-06-28","0134","5","0902 (DEBITO)","25953.28","PUNTO"],
                ["achaguas","3","2024-06-28","0108","3","179 (DEBITO)","7368.30","PUNTO"],
                ["achaguas","3","2024-06-28","0108","3","04144513850","909.50","Transferencia"],
                ["achaguas","3","2024-06-28","0134","5","3485984841","11205.04","Transferencia"],
                ["achaguas","3","2024-06-28","0102","2","8145","43.66","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04242982558","763.98","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04243454510","29.10","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04243468700","232.83","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04243464038","58.21","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04124749354","726.87","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04269433676","32.74","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04161418169","945.88","Transferencia"],
                ["achaguas","3","2024-06-28","ZELLE","9","1144","200.00","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04243067353","727.60","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04264307251","909.50","Transferencia"],
                ["achaguas","3","2024-06-28","0108","3","04140495665","909.50","Transferencia"],
                ["achaguas","3","2024-06-29","0134","5","0903 (DEBITO)","31844.78","PUNTO"],
                ["achaguas","3","2024-06-29","0134","5","0903 (CREDITO)","1328.00","PUNTO"],
                ["achaguas","3","2024-06-29","0134","5","000219 (DEBITO)","48444.54","PUNTO"],
                ["achaguas","3","2024-06-29","0134","5","000219 (CREDITO)","16392.83","PUNTO"],
                ["achaguas","3","2024-06-29","0108","3","180 (DEBITO)","20484.29","PUNTO"],
                ["achaguas","3","2024-06-29","0108","3","04243247451","160.07","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243125534","2310.13","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04164068210","98.23","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243521075","4458.37","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04167482353","5467.91","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04264470694","6512.02","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243521075","29.10","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04124155798","101.86","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04126336468","14151.82","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04124235921","4693.02","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04124235921","447.47","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243347921","9058.62","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04264401559","502.04","Transferencia"],
                ["achaguas","3","2024-06-29","ZELLE","9","1902","23.74","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243464689","72.40","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243035230","163.71","Transferencia"],
                ["achaguas","3","2024-06-29","0108","3","04243204910","502.04","Transferencia"],
                ["achaguas","3","2024-06-30","0134","5","0904 (DEBITO)","18258.38","PUNTO"],
                ["achaguas","3","2024-06-30","0108","3","181 (DEBITO)","1698.67","PUNTO"],
                ["achaguas","3","2024-06-30","0134","5","000220 (DEBITO)","3695.90","PUNTO"],
                ["achaguas","3","2024-06-30","0108","3","04143922983","327.42","Transferencia"],
                ["achaguas","3","2024-06-30","0108","3","04124631180","432.92","Transferencia"],
                ["achaguas","3","2024-06-30","0108","3","04143538150","47.29","Transferencia"],
                ["achaguas","3","2024-06-30","ZELLE","9","4507","476.00","Transferencia"],
                ["achaguas","3","2024-06-30","0108","3","04269762095","727.60","Transferencia"],
                ["achaguas","3","2024-06-30","0108","3","04143951598","523.87","Transferencia"],
                ["achaguas","3","2024-06-30","0108","3","04269762095","90.95","Transferencia"],
                ["achaguas","3","2024-07-01","0134","5","000221 (DEBITO)","17210.04","PUNTO"],
                ["achaguas","3","2024-07-01","0134","5","0905 (DEBITO)","51679.75","PUNTO"],
                ["achaguas","3","2024-07-01","0108","3","182 (DEBITO)","43316.00","PUNTO"],
                ["achaguas","3","2024-07-01","0108","3","04166454878","1338.78","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04166454878","69.12","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04149490932","2288.30","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04144782860","323.78","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04162948659","83.67","Transferencia"],
                ["achaguas","3","2024-07-01","ZELLE","9","5000","70.00","Transferencia"],
                ["achaguas","3","2024-07-01","0102","2","001088069098","980.08","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04160121550","54.57","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04264655130","249.57","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04164359586","378.35","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04163433389","909.50","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04243351739","1418.82","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04145864576","422.01","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04264401559","54.57","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04243464038","58.94","Transferencia"],
                ["achaguas","3","2024-07-01","0102","2","000021002100","529.33","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04269484841","990.63","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04167796740","800.00","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04145868693","309.23","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04145863878","6512.02","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04264306780","356.52","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04243232084","1819.00","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04243144599","7239.62","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04141440308","3368.79","Transferencia"],
                ["achaguas","3","2024-07-01","0108","3","04243741821","3092.30","Transferencia"],
                ["achaguas","3","2024-07-02","0134","5","000222 (DEBITO)","37498.93","PUNTO"],
                ["achaguas","3","2024-07-02","0134","5","000222 (CREDITO)","544.00","PUNTO"],
                ["achaguas","3","2024-07-02","0134","5","0906 (DEBITO)","12653.47","PUNTO"],
                ["achaguas","3","2024-07-02","0108","3","183 (DEBITO)","30467.55","PUNTO"],
                ["achaguas","3","2024-07-02","0108","3","04145656514","308.00","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04243454938","91.13","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04141444267","14.58","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04160357180","6512.16","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04269081164","4702.05","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04243292825","720.32","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04265401592","1640.25","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04166145805","47.21","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04166145805","422.01","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04141444267","40.10","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04263435937","185.90","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04261324408","361.98","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04264470694","5030.10","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","299650528","94041.47","Transferencia"],
                ["achaguas","3","2024-07-02","ZELLE","9","5955","33.00","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04266440594","7253.55","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04166040383","364.50","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04126015012","14215.50","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04264470694","9440.55","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04145864576","58.68","Transferencia"],
                ["achaguas","3","2024-07-02","0108","3","04128817709","4009.50","Transferencia"],
                ["bruzual","5","2024-06-27","0134","5","000173 (DEBITO)","12951.86","PUNTO"],
                ["bruzual","5","2024-06-27","0108","3","04149457489","65.48","Transferencia"],
                ["bruzual","5","2024-06-27","0108","3","04164360973","100.05","Transferencia"],
                ["bruzual","5","2024-06-27","0108","3","04245540601","29.10","Transferencia"],
                ["bruzual","5","2024-06-27","0102","2","057753","974.98","Transferencia"],
                ["bruzual","5","2024-06-27","0102","2","729176","2439.28","Transferencia"],
                ["bruzual","5","2024-06-27","0102","2","154280","2724.86","Transferencia"],
                ["bruzual","5","2024-06-27","ZELLE","9","5654","296.93","Transferencia"],
                ["bruzual","5","2024-06-27","0108","3","04164091246","505.68","Transferencia"],
                ["bruzual","5","2024-06-28","0134","5","000174 (DEBITO)","8934.04","PUNTO"],
                ["bruzual","5","2024-06-28","0108","3","04260636482","1090.54","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04143578548","262.08","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04143735938","345.80","Transferencia"],
                ["bruzual","5","2024-06-28","0102","2","244138","6879.60","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04145646481","575.12","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04245593374","469.56","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04164091246","80.08","Transferencia"],
                ["bruzual","5","2024-06-28","0102","2","792938","4695.60","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","59005","749.84","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04245526466","1055.60","Transferencia"],
                ["bruzual","5","2024-06-28","0108","3","04245783032","673.40","Transferencia"],
                ["bruzual","5","2024-06-28","0102","2","606228","118.30","Transferencia"],
                ["bruzual","5","2024-06-29","0134","5","000175 (DEBITO)","45398.69","PUNTO"],
                ["bruzual","5","2024-06-29","0108","3","04164006651","53.87","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04164006651","128.00","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04164267212","334.88","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04164006651","968.24","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","414946","4695.60","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","128631","1199.02","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","910584","6515.60","Transferencia"],
                ["bruzual","5","2024-06-29","ZELLE","9","1386","150.00","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04145743866","54.60","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","618706","163.80","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04143735938","473.20","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","687149","0.60","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","685657","6515.00","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04245951398","687.96","Transferencia"],
                ["bruzual","5","2024-06-29","0102","2","857244","105.56","Transferencia"],
                ["bruzual","5","2024-06-29","0108","3","04164267212","32.76","Transferencia"],
                ["bruzual","5","2024-06-30","0134","5","000176","23846.77","PUNTO"],
                ["bruzual","5","2024-06-30","0108","3","04143732704","728.00","Transferencia"],
                ["bruzual","5","2024-06-30","0108","3","04167789477","724.36","Transferencia"],
                ["bruzual","5","2024-06-30","0108","3","04245561347","4513.60","Transferencia"],
                ["bruzual","5","2024-06-30","0108","3","04145102966","396.76","Transferencia"],
                ["bruzual","5","2024-07-01","0134","5","000177 (DEBITO)","52920.62","PUNTO"],
                ["bruzual","5","2024-07-01","0102","2","580333","187.46","Transferencia"],
                ["bruzual","5","2024-07-01","0102","2","729603","32.80","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04260644727","15231.92","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04243504796","506.52","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04244527750","397.20","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04245540601","91.10","Transferencia"],
                ["bruzual","5","2024-07-01","0102","2","086520","233.22","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04245593374","1453.96","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04145617741","1931.32","Transferencia"],
                ["bruzual","5","2024-07-01","0108","3","04245756392","6377.00","Transferencia"],
                ["bruzual","5","2024-07-02","0134","5","000178 (DEBITO)","17872.67","PUNTO"],
                ["bruzual","5","2024-07-02","0108","3","04143732704","637.88","Transferencia"],
                ["bruzual","5","2024-07-02","0102","2","588917","4144.37","Transferencia"],
                ["bruzual","5","2024-07-02","0108","3","04145536843","317.12","Transferencia"],
                ["bruzual","5","2024-07-02","0108","3","04245756392","38272.79","Transferencia"],
                ["bruzual","5","2024-07-02","0102","2","438949","193.19","Transferencia"],
                ["bruzual","5","2024-07-02","0108","3","04164267212","152.36","Transferencia"],
                ["bruzual","5","2024-07-02","0108","3","04164267212","156.74","Transferencia"],
                ["calabozo","8","2024-06-27","0108","3","548 (DEBITO)","51598.85","PUNTO"],
                ["calabozo","8","2024-06-27","0134","5","0016 (DEBITO)","72897.64","PUNTO"],
                ["calabozo","8","2024-06-27","0134","5","0016 (CREDITO)","582.08","PUNTO"],
                ["calabozo","8","2024-06-27","0134","5","0227 (CREDITO)","2255.65","PUNTO"],
                ["calabozo","8","2024-06-27","0134","5","0227 (DEBITO)","70822.03","PUNTO"],
                ["calabozo","8","2024-06-27","0108","3","335 (DEBITO)","116878.09","PUNTO"],
                ["calabozo","8","2024-06-27","0108","3","703 (DEBITO)","64533.93","PUNTO"],
                ["calabozo","8","2024-06-27","0102","2","9930","110.96","Transferencia"],
                ["calabozo","8","2024-06-27","ZELLE","9","2525","59.00","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","2205","618.46","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","9746.","309.23","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","7473","87.31","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","7094","2874.02","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","87902","381.63","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","1941","200.09","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","4946","916.78","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","7386","54.57","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","6459","261.94","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","8714","400.18","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","2743","862.03","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","0187","696.68","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","0096","163.71","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","1285","683.94","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","1940","7294.19","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","6670","214.64","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","1921","43.66","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","3122","109.14","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5485","647.56","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","57698","1839.01","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","68705","278.31","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","6133","29.10","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5634","289.22","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","7593","49.11","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5363","54.57","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","0843","200.09","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","21219","198.27","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5649","92.00","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","01816","796.72","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","31383","480.22","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","37787","174.62","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","13810","719.96","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","3518","301.95","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","7683","221.92","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5521","184.81","Transferencia"],
                ["calabozo","8","2024-06-27","0108","3","04124133950","9127.74","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","49717","327.42","Transferencia"],
                ["calabozo","8","2024-06-27","0108","3","04124133950","218.28","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5800","618.46","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","9778","9058.62","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","2838","894.95","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","9359","261.94","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","4256","188.45","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5724","1782.62","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","9535","28.38","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","9708","578.44","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","4221","1782.62","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","5686","196.45","Transferencia"],
                ["calabozo","8","2024-06-27","0102","2","4126","320.14","Transferencia"],
                ["calabozo","8","2024-06-27","0108","3","04122925784","720.32","Transferencia"],
                ["calabozo","8","2024-06-28","0134","5","0017 (DEBITO)","51612.20","PUNTO"],
                ["calabozo","8","2024-06-28","0134","5","0017 (CREDITO)","265.00","PUNTO"],
                ["calabozo","8","2024-06-28","0108","3","549 (DEBITO)","18530.40","PUNTO"],
                ["calabozo","8","2024-06-28","0134","5","0228 (CREDITO)","976.00","PUNTO"],
                ["calabozo","8","2024-06-28","0134","5","0228 (DEBITO)","58584.57","PUNTO"],
                ["calabozo","8","2024-06-28","0108","3","704 (DEBITO)","75358.68","PUNTO"],
                ["calabozo","8","2024-06-28","0108","3","336 (DEBITO)","67980.31","PUNTO"],
                ["calabozo","8","2024-06-28","0102","2","2040","3967.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","7684","54.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","1832","629.72","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","301495","137.96","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","2015","345.44","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","3096","840.84","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","6823","360.36","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","8677","3749.20","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","28540","5103.28","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","2222","5059.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","6611","6963.32","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","6174","6515.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","6035","364.00","Transferencia"],
                ["calabozo","8","2024-06-28","ZELLE","9","55555","105.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","0616","161.98","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","7606","240.24","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","1173","418.60","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","00745","360.36","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04143344927","1139.32","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","11378","8626.80","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243760262","1346.80","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","8460","291.20","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","5878","35.00","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","9607","25.48","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04128679991","196.56","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","5157","14.56","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","2916","30.94","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","14946","651.20","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","2796","2143.96","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","1654","283.92","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243265214","273.00","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04143344927","354.17","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04144518618","80.08","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04143344927","491.40","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04267349079","163.80","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04126732242","109.20","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04145988477","2511.60","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243122330","214.76","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04125622319","5569.20","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04121378506","567.84","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243518423","418.60","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243447869","49.14","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243344489","182.00","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","4968","87.36","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243430237","109.20","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04144782282","399.67","Transferencia"],
                ["calabozo","8","2024-06-28","0108","3","04243134938","502.32","Transferencia"],
                ["calabozo","8","2024-06-28","0102","2","78445","9427.60","Transferencia"],
                ["calabozo","8","2024-06-29","0134","5","0229 (DEBITO)","73362.68","PUNTO"],
                ["calabozo","8","2024-06-29","0108","3","705 (DEBITO)","83967.63","PUNTO"],
                ["calabozo","8","2024-06-29","0134","5","0018 (DEBITO)","56011.85","PUNTO"],
                ["calabozo","8","2024-06-29","0134","5","0018 (CREDITO)","300.00","PUNTO"],
                ["calabozo","8","2024-06-29","0108","3","550 (DEBITO)","79248.29","PUNTO"],
                ["calabozo","8","2024-06-29","0108","3","337 (DEBITO)","89088.04","PUNTO"],
                ["calabozo","8","2024-06-29","0102","2","8015","50.96","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3986","220.00","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","5850","231.50","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","7342","371.28","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04124465302","69.16","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4802","138.32","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8972","325.78","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04260470649","109.20","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0052","531.44","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04129691624","4167.80","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04125665349","70.98","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243185695","170.72","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3281","162995.08","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3223","163060.30","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0168","211.12","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04129691624","65.52","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6507","316.68","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243081814","2090.09","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","9838","118.30","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8531","5077.07","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","83820","391.30","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","4739","247.50","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","94639","910.00","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04123159439","964.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","208154","309.40","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6377","156.52","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","2730","128.13","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3461","527.80","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243013741","436.80","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243084692","192.92","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8883","429.52","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04129792190","4695.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8034","316.68","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6659","105.56","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3999","174.72","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4922","684.32","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","1587","2147.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","2860","109.20","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4614","236.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","2836","11975.60","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04122925784","249.34","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243011698","109.20","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0558","18000.00","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04124062581","360.36","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243643907","36.40","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","7548","309.40","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4933","1081.08","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04243360942","2023.84","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0196","152.88","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6975","309.40","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04125623225","99.01","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8183","251.16","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3166","43.68","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","5441","101.92","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","5195","618.80","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","5753","54.00","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8537","273.00","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","7263","65.52","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4249","69.16","Transferencia"],
                ["calabozo","8","2024-06-29","0108","3","04141467281","116.48","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4263","1783.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","4071","109.20","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","14982","40.04","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3974","691.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6965","134.68","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","27686","360.36","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3755","54.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","6662","258.44","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0869","56.42","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","3761","251.16","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","7796","61.88","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","0335","131.04","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","2908","5059.60","Transferencia"],
                ["calabozo","8","2024-06-29","0102","2","8207","4695.60","Transferencia"],
                ["calabozo","8","2024-06-30","0134","5","000230 (DEBITO)","61114.00","PUNTO"],
                ["calabozo","8","2024-06-30","0108","3","706 (DEBITO)","29315.52","PUNTO"],
                ["calabozo","8","2024-06-30","0108","3","338 (DEBITO)","22775.36","PUNTO"],
                ["calabozo","8","2024-06-30","0134","5","0019 (DEBITO)","21974.84","PUNTO"],
                ["calabozo","8","2024-06-30","0108","3","551 (DEBITO)","24390.01","PUNTO"],
                ["calabozo","8","2024-06-30","0108","3","04124465302","158.34","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","5182","116.48","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","1227","125.58","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","9051","71.06","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","28544","233.22","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","9527","419.06","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","41639","94.74","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","00382","61.95","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","88198","1346.09","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","9516","180.18","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","2890","251.44","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","0412","298.81","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","3340","215.00","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","2349","65.52","Transferencia"],
                ["calabozo","8","2024-06-30","0108","3","04126607439","107.50","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","8434","89.18","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","9123","153.05","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","7776","14.56","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","0038","615.84","Transferencia"],
                ["calabozo","8","2024-06-30","0108","3","04145799347","6522.76","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","1153","40.08","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","9193","5429.56","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","1807","65.52","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","17378","280.59","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","8488","54.66","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","3568","32.80","Transferencia"],
                ["calabozo","8","2024-06-30","0102","2","0026","691.60","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","707 (DEBITO)","63397.51","PUNTO"],
                ["calabozo","8","2024-07-01","0134","5","0020 (DEBITO)","57249.51","PUNTO"],
                ["calabozo","8","2024-07-01","0134","5","0020 (CREDITO)","70.00","PUNTO"],
                ["calabozo","8","2024-07-01","0108","3","339 (DEBITO)","57563.20","PUNTO"],
                ["calabozo","8","2024-07-01","0108","3","552 (DEBITO)","91609.68","PUNTO"],
                ["calabozo","8","2024-07-01","0134","5","0231 (CREDITO)","1344.00","PUNTO"],
                ["calabozo","8","2024-07-01","0134","5","0231 (DEBITO)","102077.24","PUNTO"],
                ["calabozo","8","2024-07-01","0102","2","2177","127.54","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5374","320.67","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6967","43.73","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5553","951.08","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4242","455.50","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","38377","286.05","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5526","548.79","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","9917","32.80","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","2009","4303.56","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4128","251.00","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","3128","287.88","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5591","1136.93","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","3078","5999.85","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","48705","251.07","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","78613","215.00","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","8144","160.34","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","7288","4372.80","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","3564","25.51","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04124152193","779.82","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5582","244.15","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","9420","619.48","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04243718729","112.96","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4008","65.59","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","91686","2404.62","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6105","76.89","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04124050344","87.46","Transferencia"],
                ["calabozo","8","2024-07-01","ZELLE","9","2525","74.00","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4266","142.12","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","3225","163.98","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04243060207","69.24","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","0199","215.00","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","9510","972.95","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4362","470.08","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","3227","69.96","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","1449","58.30","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","0715","2186.40","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4412","502.87","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","8411","142.12","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","2052","5662.78","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6651","211.35","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6570","284.23","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","06070","240.50","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","0069","1388.36","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4529","309.74","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","4713","251.44","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","5636","1081.54","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","2848","6377.00","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","28528","4336.36","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","0634","612.01","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","80066","54.66","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","555838","213.17","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","2690","9765.92","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","0370","2514.36","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6620","91.10","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","9792","628.59","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","6180","120.25","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04243146703","80.17","Transferencia"],
                ["calabozo","8","2024-07-01","0108","3","04243146703","338.89","Transferencia"],
                ["calabozo","8","2024-07-01","0102","2","1410","2514.36","Transferencia"],
                ["calabozo","8","2024-07-02","0134","5","0232 (CREDITO)","145.00","PUNTO"],
                ["calabozo","8","2024-07-02","0134","5","0232 (DEBITO)","103508.65","PUNTO"],
                ["calabozo","8","2024-07-02","0108","3","340 (DEBITO)","113498.67","PUNTO"],
                ["calabozo","8","2024-07-02","0108","3","553 (DEBITO)","71247.39","PUNTO"],
                ["calabozo","8","2024-07-02","0134","5","0021 (DEBITO)","78636.07","PUNTO"],
                ["calabozo","8","2024-07-02","0108","3","708 (DEBITO)","116643.71","PUNTO"],
                ["calabozo","8","2024-07-02","0102","2","24380","255.08","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3353","364.40","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3515","65.59","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9402","58.30","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","0515","120.25","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2756","163.98","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","45352","47.37","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7949","273.30","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9039","13843.71","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","6782","207.77","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5208","528.53","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","8222","290.87","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","1286","91.13","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2009","6160.05","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","443210","89.67","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","0982","112.78","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","11339","211.41","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","1595","85.66","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5588","622.76","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","93427","3280.50","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7224","3462.75","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","8675","309.74","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","6096","360.76","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","1556","54.68","Transferencia"],
                ["calabozo","8","2024-07-02","0108","3","04140538261","218.70","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2004","818.30","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5863","127.58","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7132","4756.73","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7682","251.51","Transferencia"],
                ["calabozo","8","2024-07-02","0108","3","04144460789","167.67","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","83604","280.67","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","22503","455.26","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2404","109.35","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9856","222.34","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","8548","4702.05","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","8412","4702.05","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5766","109.35","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2340","65.61","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7258","6524.55","Transferencia"],
                ["calabozo","8","2024-07-02","0108","3","04123159439","360.86","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5061","349.92","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","4047164","4702.05","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3777","127.54","Transferencia"],
                ["calabozo","8","2024-07-02","ZELLE","9","2020","36.60","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3317","3279.60","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2751","164.03","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","5677","51.03","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","0696","54.68","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","0127","72.88","Transferencia"],
                ["calabozo","8","2024-07-02","ZELLE","9","2020","39.80","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9001","29.16","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3309","165.85","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","543062","21506.05","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","6455","120.28","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","22245","366.32","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","6511","460.24","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7538","973.22","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","1731","105.70","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","45384","419.18","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","1086","4844.21","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9915","5226.93","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","2826","36.45","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","9939","2570.02","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","3421","109.35","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","7164","21.87","Transferencia"],
                ["calabozo","8","2024-07-02","0102","2","0679","109.35","Transferencia"],
                ["elsaman","4","2024-06-27","0134","5","000220 (DEBITO)","32882.42","PUNTO"],
                ["elsaman","4","2024-06-27","0108","3","04260846552","8331.02","Transferencia"],
                ["elsaman","4","2024-06-27","0108","3","04267389458","978.62","Transferencia"],
                ["elsaman","4","2024-06-27","0108","3","04261417655","2728.43","Transferencia"],
                ["elsaman","4","2024-06-27","0108","3","04261326851","2728.43","Transferencia"],
                ["elsaman","4","2024-06-27","0108","3","04266108543","1048.32","Transferencia"],
                ["elsaman","4","2024-06-27","0108","3","04163129310","36.40","Transferencia"],
                ["elsaman","4","2024-06-29","0134","5","000222 (DEBITO)","30242.94","PUNTO"],
                ["elsaman","4","2024-06-29","0108","3","04164459040","318.50","Transferencia"],
                ["elsaman","4","2024-06-29","0108","3","04169458428","323.96","Transferencia"],
                ["elsaman","4","2024-06-29","ZELLE","9","00100","100.00","Transferencia"],
                ["elsaman","4","2024-06-29","0108","3","001081980369","77.97","Transferencia"],
                ["elsaman","4","2024-06-30","0134","5","000223 (DEBITO)","1414.98","PUNTO"],
                ["elsaman","4","2024-06-30","0108","3","04167319989","3439.80","Transferencia"],
                ["elsaman","4","2024-06-30","0108","3","001084172126","200.20","Transferencia"],
                ["elsaman","4","2024-06-30","0102","2","04260497782","342.16","Transferencia"],
                ["elsaman","4","2024-07-01","0134","5","000224 (DEBITO)","7291.74","PUNTO"],
                ["elsaman","4","2024-07-01","0108","3","04268340202","106.40","Transferencia"],
                ["elsaman","4","2024-07-01","0108","3","04167319989","6377.00","Transferencia"],
                ["elsaman","4","2024-07-01","0108","3","04161541976","207.71","Transferencia"],
                ["elsaman","4","2024-07-01","0108","3","041834827306.000740477944","5320.24","Transferencia"],
                ["elsaman","4","2024-07-02","0134","5","000225 (DEBITO)","18130.83","PUNTO"],
                ["elsaman","4","2024-07-02","0108","3","04267788536","5065.16","Transferencia"],
                ["elsaman","4","2024-07-02","0108","3","04261417655","506.52","Transferencia"],
                ["elsaman","4","2024-07-02","0108","3","04244223655","364.40","Transferencia"],
                ["elorza","1","2024-07-01","0134","5","06 (DEBITO)","21199.00","PUNTO"],
                ["elorza","1","2024-07-01","0102","2","672866984627","856.34","Transferencia"],
                ["elorza","1","2024-07-01","0102","2","001088601595","2733.00","Transferencia"],
                ["elorza","1","2024-07-01","0102","2","001090345832","3279.60","Transferencia"],
                ["elorza","1","2024-07-01","0102","2","041834975478","674.14","Transferencia"],
                ["elorza","1","2024-07-02","0134","5","07 (DEBITO)","2855.89","PUNTO"],
                ["elorza","1","2024-07-02","0102","2","672867845845","8713.94","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","672867848912","875.04","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","041845353372","134.90","Transferencia"],
                ["elorza","1","2024-07-02","0108","3","0416-8655823","947.96","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","003192319226","167.72","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","004923492385","1499.96","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","001092968227","648.99","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","672867945422","1451.11","Transferencia"],
                ["elorza","1","2024-07-02","0108","3","0416-1973802","721.91","Transferencia"],
                ["elorza","1","2024-07-02","0108","3","0416-1406132","400.33","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","0590542299338","435.70","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","008739873976","4737.25","Transferencia"],
                ["elorza","1","2024-07-02","0102","2","041846082725","916.97","Transferencia"],
                ["guacara","14","2024-06-28","0134","5","0111 (CREDITO)","491.24","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","0111 (DEBITO)","43808.78","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","0111 (CREDITO)","4931.32","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","0111 (DEBITO)","33442.46","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","00113 (CREDITO)","3001.18","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","00113 (DEBITO)","27799.79","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","00112 (CREDITO)","141.96","PUNTO"],
                ["guacara","14","2024-06-28","0134","5","00112 (DEBITO)","15224.68","PUNTO"],
                ["guacara","14","2024-06-28","0102","2","9791","934.97","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","6310","418.37","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","6485","21.83","Transferencia"],
                ["guacara","14","2024-06-28","ZELLE","9","1554","30.90","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","7758","505.68","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","7761","45.48","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","0651","69.12","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","5833","91.00","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","9029","182.00","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","5740","338.52","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04244043563","163.80","Transferencia"],
                ["guacara","14","2024-06-28","ZELLE","9","7476","35.00","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","8035","45.50","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","8225","163.80","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","5304","345.80","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","1351","45.50","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","2752","73.71","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","1712","200.20","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04128479491","800.07","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04128479491","18.93","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04144165692","43.68","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04124866754","80.08","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","3888","182.00","Transferencia"],
                ["guacara","14","2024-06-28","0108","3","04244117322","433.16","Transferencia"],
                ["guacara","14","2024-06-28","ZELLE","9","503","161.00","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","9377","135.41","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","7621","4673.76","Transferencia"],
                ["guacara","14","2024-06-28","0102","2","8256","429.52","Transferencia"],
                ["guacara","14","2024-06-29","0134","5","00113 (CREDITO)","1548.70","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","00113 (DEBITO)","15717.21","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","0112 (CREDITO)","13325.99","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","0112 (DEBITO)","52854.19","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","00114 (CREDITO)","1044.11","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","00114 (DEBITO)","34102.19","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","0112 (CREDITO)","1095.04","PUNTO"],
                ["guacara","14","2024-06-29","0134","5","0112 (DEBITO)","85596.52","PUNTO"],
                ["guacara","14","2024-06-29","0102","2","4151","3965.42","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","0117","208.82","Transferencia"],
                ["guacara","14","2024-06-29","0108","3","04244305946","25.47","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","8487","4693.02","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","4205","1018.64","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","1135","163.71","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","8779","51.66","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","9108","352.16","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","6934","1571.62","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","4403","90.95","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","6018","729.42","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","6539","200.09","Transferencia"],
                ["guacara","14","2024-06-29","0108","3","7979","94.59","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","4430","207.37","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","3785","214.64","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","9936","767.62","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","2698","208.82","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","2835","6336.67","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","8452","4420.17","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","3550","156.43","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","5399","94.59","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","8525","691.22","Transferencia"],
                ["guacara","14","2024-06-29","0108","3","04129546333","125.51","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","9685","163.71","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","4456","7235.98","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","0384","309.23","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","1461","523.87","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","5062","865.84","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","9331","105.50","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","0670","327.42","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","1493","2666.65","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","8610","189.18","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","4402","174.62","Transferencia"],
                ["guacara","14","2024-06-29","ZELLE","9","18167141","139.00","Transferencia"],
                ["guacara","14","2024-06-29","0102","2","2412","211.00","Transferencia"],
                ["guacara","14","2024-06-29","ZELLE","9","16724632","39.00","Transferencia"],
                ["guacara","14","2024-06-30","0134","5","0113 (DEBITO)","22905.95","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","00114 (CREDITO)","47.29","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","00114 (DEBITO)","18139.61","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","00115 (CREDITO)","261.94","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","00115 (DEBITO)","12123.18","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","0113 (CREDITO)","65.48","PUNTO"],
                ["guacara","14","2024-06-30","0134","5","0113 (DEBITO)","23829.70","PUNTO"],
                ["guacara","14","2024-06-30","0108","3","04143431342","40.02","Transferencia"],
                ["guacara","14","2024-06-30","0108","3","04143431342","47.29","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","7217","272.85","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","0296","254.66","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","5231","869.48","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","5327","412.91","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","4896","376.53","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","8181","705.77","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","4316","54.57","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","3985","54.57","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","4731","1819.00","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","2082","47.29","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","0950","705.77","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","6725","10770.04","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","3863","502.04","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","2115","1273.30","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","8069","5056.82","Transferencia"],
                ["guacara","14","2024-06-30","0108","3","04124670582","65.48","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","4340","65.48","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","0321","68.39","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","8481","712.68","Transferencia"],
                ["guacara","14","2024-06-30","0102","2","0537","287.40","Transferencia"],
                ["guacara","14","2024-07-01","0134","5","0114","43081.16","PUNTO"],
                ["guacara","14","2024-07-01","0134","5","00115","18552.31","PUNTO"],
                ["guacara","14","2024-07-01","0134","5","00116","143.03","PUNTO"],
                ["guacara","14","2024-07-01","0134","5","00116","23890.28","PUNTO"],
                ["guacara","14","2024-07-01","0134","5","0114","696.01","PUNTO"],
                ["guacara","14","2024-07-01","0134","5","0114","45034.40","PUNTO"],
                ["guacara","14","2024-07-01","0102","2","9911","240.50","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","3992","612.19","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2416","32.80","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","4574","65.59","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","8531","1275.40","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2064","54.66","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","0300","134.83","Transferencia"],
                ["guacara","14","2024-07-01","0108","3","04128815000","353.10","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","4752","21.86","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","0949","612.19","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","9021","178.56","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","0986","25.51","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","8660","488.30","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","6098","551.70","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","3145","21.86","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","7976","3060.96","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","7359","251.44","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","1059","21.86","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","8338","61.95","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2604","54.66","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","9984","5458.71","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","0922","327.96","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","50060","397.20","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","9187","69.24","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","6995","892.78","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","0922","76.52","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2239","7.29","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","1023","65.59","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","3550","105.68","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2454","2758.51","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","2616","3644.00","Transferencia"],
                ["guacara","14","2024-07-01","0102","2","1927","120.25","Transferencia"],
                ["guacara","14","2024-07-02","0134","5","000116 (DEBITO)","9467.93","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","00115 (CREDITO)","1264.82","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","00115 (DEBITO)","10734.81","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","00116 (DEBITO)","5025.44","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","00117 (CREDITO)","6761.12","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","00117 (DEBITO)","25675.15","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","0115 (CREDITO)","1235.66","PUNTO"],
                ["guacara","14","2024-07-02","0134","5","0115 (DEBITO)","53915.70","PUNTO"],
                ["guacara","14","2024-07-02","0102","2","1014","178.61","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","4794","532.17","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","1117","1935.50","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","3117","54.68","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","0645","751.96","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","53703","652.46","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","7871","72.90","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","1015","2048.49","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","3090","29.16","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","16977","105.70","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","1937","58.32","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","9529","185.90","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","3357","1013.31","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","4311","1086.21","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","1592","448.34","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","2118","287.96","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","6748","251.51","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","0937","331.69","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","0684","14.58","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","5418","72.90","Transferencia"],
                ["guacara","14","2024-07-02","0102","2","1437","6378.75","Transferencia"],
                ["mantecal","2","2024-06-27","0134","5","0455 (DEBITO)","25137.06","PUNTO"],
                ["mantecal","2","2024-06-27","0114","12","0192 (DEBITO)","4986.94","PUNTO"],
                ["mantecal","2","2024-06-27","ZELLE","9","108.50","108.50","Transferencia"],
                ["mantecal","2","2024-06-27","0108","3","04269404065","556.61","Transferencia"],
                ["mantecal","2","2024-06-27","0108","3","04169429010","380.17","Transferencia"],
                ["mantecal","2","2024-06-27","ZELLE","9","6391","864.80","Transferencia"],
                ["mantecal","2","2024-06-27","0102","2","351810","589.36","Transferencia"],
                ["mantecal","2","2024-06-27","0108","3","04168490799","16152.97","Transferencia"],
                ["mantecal","2","2024-06-27","0108","3","04260567445","65.48","Transferencia"],
                ["mantecal","2","2024-06-28","0134","5","0456 (DEBITO)","32565.29","PUNTO"],
                ["mantecal","2","2024-06-28","0114","12","0193 (DEBITO)","17578.72","PUNTO"],
                ["mantecal","2","2024-06-28","0108","3","2295381","36.40","Transferencia"],
                ["mantecal","2","2024-06-28","ZELLE","9","89.60","89.60","Transferencia"],
                ["mantecal","2","2024-06-28","0102","2","242594","223.86","Transferencia"],
                ["mantecal","2","2024-06-28","ZELLE","9","00391651","38.88","Transferencia"],
                ["mantecal","2","2024-06-28","0108","3","04267323834","251.16","Transferencia"],
                ["mantecal","2","2024-06-28","0108","3","04167443471","127.40","Transferencia"],
                ["mantecal","2","2024-06-28","0108","3","04167443471","334.88","Transferencia"],
                ["mantecal","2","2024-06-28","0102","2","744897","80808.00","Transferencia"],
                ["mantecal","2","2024-06-28","0108","3","248178","5925.92","Transferencia"],
                ["mantecal","2","2024-06-29","0134","5","0457 (DEBITO)","59033.75","PUNTO"],
                ["mantecal","2","2024-06-29","0114","12","0194 (DEBITO)","14154.32","PUNTO"],
                ["mantecal","2","2024-06-29","0108","3","04245527167","160.16","Transferencia"],
                ["mantecal","2","2024-06-29","0102","2","606408","1419.60","Transferencia"],
                ["mantecal","2","2024-06-29","0108","3","04263462388","105.56","Transferencia"],
                ["mantecal","2","2024-06-29","0108","3","04162364344","138.32","Transferencia"],
                ["mantecal","2","2024-06-29","ZELLE","9","50","50.00","Transferencia"],
                ["mantecal","2","2024-06-30","0114","12","0195 (DEBITO)","936.00","PUNTO"],
                ["mantecal","2","2024-06-30","0134","5","0458 (DEBITO)","8979.42","PUNTO"],
                ["mantecal","2","2024-07-01","0134","5","0459 (DEBITO)","32566.50","PUNTO"],
                ["mantecal","2","2024-07-01","0114","12","0196 (DEBITO)","5721.82","PUNTO"],
                ["mantecal","2","2024-07-01","0102","2","7887371","211.12","Transferencia"],
                ["mantecal","2","2024-07-01","0108","3","25165","498.68","Transferencia"],
                ["mantecal","2","2024-07-01","0108","3","04242331745","142.12","Transferencia"],
                ["mantecal","2","2024-07-01","0102","2","152573","36.44","Transferencia"],
                ["mantecal","2","2024-07-01","ZELLE","9","150","150.00","Transferencia"],
                ["mantecal","2","2024-07-01","0102","2","887240","1430.63","Transferencia"],
                ["mantecal","2","2024-07-01","0102","2","378066","77.44","Transferencia"],
                ["mantecal","2","2024-07-01","0108","3","771399","1274.31","Transferencia"],
                ["mantecal","2","2024-07-01","ZELLE","9","14.90","14.90","Transferencia"],
                ["mantecal","2","2024-07-01","0102","2","818150","587.87","Transferencia"],
                ["mantecal","2","2024-07-01","0108","3","04165489335","20000.00","Transferencia"],
                ["mantecal","2","2024-07-01","0102","2","1164596","349.82","Transferencia"],
                ["mantecal","2","2024-07-02","0134","5","0460 (DEBITO)","19870.37","PUNTO"],
                ["mantecal","2","2024-07-02","0114","12","0197 (DEBITO)","7242.71","PUNTO"],
                ["mantecal","2","2024-07-02","0102","2","933270","284.31","Transferencia"],
                ["mantecal","2","2024-07-02","0102","2","523733","36.45","Transferencia"],
                ["mantecal","2","2024-07-02","0108","3","04149527856","8711.55","Transferencia"],
                ["mantecal","2","2024-07-02","0102","2","53748","368.15","Transferencia"],
                ["mantecal","2","2024-07-02","0108","3","04243609892","69.25","Transferencia"],
                ["mantecal","2","2024-07-02","0108","3","04149527856","324.41","Transferencia"],
                ["mantecal","2","2024-07-02","ZELLE","9","66","65.90","Transferencia"],
                ["mantecal","2","2024-07-02","ZELLE","9","20","20.00","Transferencia"],
                ["mantecal","2","2024-07-02","ZELLE","9","30","30.00","Transferencia"],
                ["mantecal","2","2024-07-02","ZELLE","9","15","15.00","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","00199 (DEBITO)","9448.44","PUNTO"],
                ["maracay","12","2024-06-27","0134","5","00253 (DEBITO)","27236.05","PUNTO"],
                ["maracay","12","2024-06-27","0134","5","00254 (DEBITO)","18393.25","PUNTO"],
                ["maracay","12","2024-06-27","0134","5","003 (CREDITO)","2743.05","PUNTO"],
                ["maracay","12","2024-06-27","0134","5","003 (DEBITO)","23989.69","PUNTO"],
                ["maracay","12","2024-06-27","0114","12","000105 (CREDITO)","347.17","PUNTO"],
                ["maracay","12","2024-06-27","0114","12","000174 (DEBITO)","33419.01","PUNTO"],
                ["maracay","12","2024-06-27","0108","3","947007","127.33","Transferencia"],
                ["maracay","12","2024-06-27","0134","5","772299","272486.20","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","0424-3087975","50.93","Transferencia"],
                ["maracay","12","2024-06-27","ZELLE","9","27995644","248.00","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04142275650","2168.25","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04243172969","469.30","Transferencia"],
                ["maracay","12","2024-06-27","ZELLE","9","2742","219.00","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04262383041","29.10","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","0424-355-1632","17462.40","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","0424-330-1581","409.28","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04144619794","76.40","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04128708465","993.17","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04128360025","436.20","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","0424-3803625","1746.24","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04144538169","101.86","Transferencia"],
                ["maracay","12","2024-06-27","0108","3","04243104416","87.31","Transferencia"],
                ["maracay","12","2024-06-27","0102","2","00684557","7523.24","BIOPAGO"],
                ["maracay","12","2024-06-28","0114","12","000175 (DEBITO)","10842.92","PUNTO"],
                ["maracay","12","2024-06-28","0114","12","000106 (CREDITO)","2901.89","PUNTO"],
                ["maracay","12","2024-06-28","0108","3","00200 (DEBITO)","22261.46","PUNTO"],
                ["maracay","12","2024-06-28","0134","5","000004 (DEBITO)","32795.71","PUNTO"],
                ["maracay","12","2024-06-28","0134","5","000255 (DEBITO)","38941.12","PUNTO"],
                ["maracay","12","2024-06-28","0108","3","04243280194","5089.93","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","04243280194","585.35","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","04121795374","160.16","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","0412-1542000","181.90","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","04243229471","298.48","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","3819","377.47","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","04243125957","58.24","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","0424-309-2621","910.00","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","04128526554","218.40","Transferencia"],
                ["maracay","12","2024-06-28","0102","2","5394","196.56","Transferencia"],
                ["maracay","12","2024-06-28","0108","3","0412-2920159","407.68","Transferencia"],
                ["maracay","12","2024-06-28","0102","2","00684557","31835.43","BIOPAGO"],
                ["maracay","12","2024-06-29","0134","5","0005 (CREDITO)","19415.92","PUNTO"],
                ["maracay","12","2024-06-29","0134","5","0005 (DEBITO)","58470.06","PUNTO"],
                ["maracay","12","2024-06-29","0134","5","000256 (DEBITO)","25910.28","PUNTO"],
                ["maracay","12","2024-06-29","0134","5","000257 (DEBITO)","25715.54","PUNTO"],
                ["maracay","12","2024-06-29","0114","12","000107 (CREDITO)","4939.38","PUNTO"],
                ["maracay","12","2024-06-29","0114","12","000176 (DEBITO)","52052.45","PUNTO"],
                ["maracay","12","2024-06-29","0108","3","00201 (CREDITO)","2289.66","PUNTO"],
                ["maracay","12","2024-06-29","0108","3","00201 (DEBITO)","21037.22","PUNTO"],
                ["maracay","12","2024-06-29","0108","3","04145887860","1172.08","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04243045005","182.00","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04243291929","229.32","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04243154837","101.92","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04263341749","178.36","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0424-339-3988","21.84","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0424-339-3988","69.16","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0424-3138329","262.08","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0412-8987018","163.80","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04124601562","2595.32","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04128909571","17399.20","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0124-4648168","80.08","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04243243850","1092.00","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04243646646","407.68","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04128372307","364.00","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0412-4629539","502.32","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0412-0436174","351.26","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0424-3162279","60.06","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0412-1461195","116.48","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","0412-4230449","1419.60","Transferencia"],
                ["maracay","12","2024-06-29","0108","3","04142257250","127.40","Transferencia"],
                ["maracay","12","2024-06-30","0114","12","000108 (CREDITO)","182.00","PUNTO"],
                ["maracay","12","2024-06-30","0114","12","000177 (CREDITO)","41024.46","PUNTO"],
                ["maracay","12","2024-06-30","0134","5","000258 (DEBITO)","16950.98","PUNTO"],
                ["maracay","12","2024-06-30","0134","5","0006 (DEBITO)","23300.09","PUNTO"],
                ["maracay","12","2024-06-30","0108","3","0412-8937977","178.36","Transferencia"],
                ["maracay","12","2024-06-30","0108","3","0414-3282576","495.04","Transferencia"],
                ["maracay","12","2024-06-30","0108","3","04149458350","141.96","Transferencia"],
                ["maracay","12","2024-06-30","0108","3","04128996091","138.32","Transferencia"],
                ["maracay","12","2024-06-30","0108","3","04128996091","43.68","Transferencia"],
                ["maracay","12","2024-06-30","0108","3","0424-3361356","163.80","Transferencia"],
                ["maracay","12","2024-07-01","0134","5","000007 (DEBITO)","25617.99","PUNTO"],
                ["maracay","12","2024-07-01","0108","3","000178 (DEBITO)","46268.27","PUNTO"],
                ["maracay","12","2024-07-01","0108","3","000109 (CREDITO)","21.86","PUNTO"],
                ["maracay","12","2024-07-01","0134","5","000259 (DEBITO)","38542.55","PUNTO"],
                ["maracay","12","2024-07-01","0108","3","00202 (DEBITO)","16365.01","PUNTO"],
                ["maracay","12","2024-07-01","0108","3","04128825251","185.84","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243060454","217.55","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04144560820","54.66","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04124448600","696.00","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04124448600","428.17","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04124094906","320.31","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04120422611","2149.96","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04120422611","36.44","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243229471","1144.22","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04144776270","262.37","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04162366310","647.90","Transferencia"],
                ["maracay","12","2024-07-01","0102","2","1686","1300.91","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04123438937","2425.08","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243646646","178.56","Transferencia"],
                ["maracay","12","2024-07-01","0134","5","7802","659.56","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243090931","6522.76","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04123440606","102.03","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243077349","242.47","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243299917","1384.76","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243299917","5757.52","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04244183625","284.23","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243199178","229.94","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243151155","802.04","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04125337056","131.18","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04243390944","247.79","Transferencia"],
                ["maracay","12","2024-07-01","0102","2","6382","799.86","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04164264355","455.50","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04144039572","36.44","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","0412035589","142.12","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04128643787","4555.00","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","04129402762","130.46","Transferencia"],
                ["maracay","12","2024-07-01","0108","3","44739","182.20","Transferencia"],
                ["maracay","12","2024-07-02","0134","5","000008 (DEBITO)","47038.14","PUNTO"],
                ["maracay","12","2024-07-02","0134","5","000260 (DEBITO)","38273.98","PUNTO"],
                ["maracay","12","2024-07-02","0108","3","203 (DEBITO)","4582.93","PUNTO"],
                ["maracay","12","2024-07-02","0108","3","203 (CREDITO)","617.00","PUNTO"],
                ["maracay","12","2024-07-02","0114","12","000110 (CREDITO)","1030.58","PUNTO"],
                ["maracay","12","2024-07-02","0114","12","000179 (DEBITO)","55987.33","PUNTO"],
                ["maracay","12","2024-07-02","0108","3","04243256651","91.10","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04128534742","1086.21","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04141441895","736.29","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04243526018","2730.11","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04243526018","543.11","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04145873391","185.90","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04128821145","138.51","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","0421","3647.19","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","7281","1275.75","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","2419","105.70","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","66169","82.01","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","04143476344","430.11","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","9701","761.81","Transferencia"],
                ["maracay","12","2024-07-02","0108","3","3668","174.96","Transferencia"],
                ["sanfernando1","6","2024-06-27","0134","5","0235 (CREDITO)","360.16","PUNTO"],
                ["sanfernando1","6","2024-06-27","0134","5","0235 (DEBITO)","51793.23","PUNTO"],
                ["sanfernando1","6","2024-06-27","0134","5","000220 (DEBITO)","35771.59","PUNTO"],
                ["sanfernando1","6","2024-06-27","0108","3","079 (DEBITO)","48991.96","PUNTO"],
                ["sanfernando1","6","2024-06-27","0102","2","8464","181.90","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","1620","290.68","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","5897","36.38","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","5747","38381.88","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","4522","763.98","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","0506","451.11","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","1015","698.50","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","3867","105.50","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","3866","50.93","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","3513","56.39","Transferencia"],
                ["sanfernando1","6","2024-06-27","0102","2","0973","69.12","Transferencia"],
                ["sanfernando1","6","2024-06-28","0134","5","000221 (DEBITO)","25610.00","PUNTO"],
                ["sanfernando1","6","2024-06-28","0134","5","0236 (CREDITO)","720.72","PUNTO"],
                ["sanfernando1","6","2024-06-28","0134","5","0236 (DEBITO)","19083.26","PUNTO"],
                ["sanfernando1","6","2024-06-28","0134","5","080 (DEBITO)","71352.04","PUNTO"],
                ["sanfernando1","6","2024-06-28","0134","5","000084 (DEBITO)","58983.88","PUNTO"],
                ["sanfernando1","6","2024-06-28","0134","5","000084 (CREDITO)","251.16","PUNTO"],
                ["sanfernando1","6","2024-06-28","0102","2","5418","182.00","Transferencia"],
                ["sanfernando1","6","2024-06-28","0102","2","2125","18.20","Transferencia"],
                ["sanfernando1","6","2024-06-28","0102","2","2302","115.39","Transferencia"],
                ["sanfernando1","6","2024-06-28","0102","2","4426","32.03","Transferencia"],
                ["sanfernando1","6","2024-06-29","0134","5","081 (DEBITO)","56190.25","PUNTO"],
                ["sanfernando1","6","2024-06-29","0134","5","0237 (DEBITO)","70246.12","PUNTO"],
                ["sanfernando1","6","2024-06-29","0134","5","000085 (DEBITO)","71528.87","PUNTO"],
                ["sanfernando1","6","2024-06-29","0134","5","0237 (CREDITO)","1272.20","PUNTO"],
                ["sanfernando1","6","2024-06-29","0134","5","0237 (DEBITO)","72207.94","PUNTO"],
                ["sanfernando1","6","2024-06-29","ZELLE","9","3770","32.40","Transferencia"],
                ["sanfernando1","6","2024-06-30","0108","3","082 (DEBITO)","20786.40","PUNTO"],
                ["sanfernando1","6","2024-06-30","0134","5","0238 (CREDITO)","1215.76","PUNTO"],
                ["sanfernando1","6","2024-06-30","0134","5","0238 (DEBITO)","8483.78","PUNTO"],
                ["sanfernando1","6","2024-06-30","0134","5","0223 (DEBITO)","25429.00","PUNTO"],
                ["sanfernando1","6","2024-06-30","0102","2","8909","36.40","Transferencia"],
                ["sanfernando1","6","2024-06-30","0102","2","6284","72.80","Transferencia"],
                ["sanfernando1","6","2024-06-30","0102","2","5973","141.96","Transferencia"],
                ["sanfernando1","6","2024-06-30","0102","2","1627","651.56","Transferencia"],
                ["sanfernando1","6","2024-06-30","0102","2","6788","7243.60","Transferencia"],
                ["sanfernando1","6","2024-07-01","0134","5","0239 (CREDITO)","215.00","PUNTO"],
                ["sanfernando1","6","2024-07-01","0134","5","0239 (DEBITO)","45097.02","PUNTO"],
                ["sanfernando1","6","2024-07-01","0134","5","0224 (DEBITO)","66651.14","PUNTO"],
                ["sanfernando1","6","2024-07-01","0134","5","083 (DEBITO)","32602.91","PUNTO"],
                ["sanfernando1","6","2024-07-01","0134","5","000086 (DEBITO)","79107.19","PUNTO"],
                ["sanfernando1","6","2024-07-01","0134","5","000086 (CREDITO)","302.45","PUNTO"],
                ["sanfernando1","6","2024-07-01","0102","2","4171","149.55","Transferencia"],
                ["sanfernando1","6","2024-07-01","0102","2","3503","473.72","Transferencia"],
                ["sanfernando1","6","2024-07-01","0102","2","0318","1876.66","Transferencia"],
                ["sanfernando1","6","2024-07-01","0108","3","04128313188","188.10","Transferencia"],
                ["sanfernando1","6","2024-07-01","0108","3","04128313188","160.34","Transferencia"],
                ["sanfernando1","6","2024-07-02","0134","5","0240 (DEBITO)","46272.54","PUNTO"],
                ["sanfernando1","6","2024-07-02","0134","5","000087 (DEBITO)","25864.57","PUNTO"],
                ["sanfernando1","6","2024-07-02","0134","5","0225 (DEBITO)","35733.21","PUNTO"],
                ["sanfernando1","6","2024-07-02","0134","5","0225 (CREDITO)","547.00","PUNTO"],
                ["sanfernando1","6","2024-07-02","0102","2","7315","1421.55","Transferencia"],
                ["sanfernando1","6","2024-07-02","0175","6","9050","267543.77","Transferencia"],
                ["sanfernando2","7","2024-06-27","0134","5","0237 (DEBITO)","44676.58","PUNTO"],
                ["sanfernando2","7","2024-06-27","0134","5","0237 (CREDITO)","401.00","PUNTO"],
                ["sanfernando2","7","2024-06-27","0134","5","0083 (CREDITO)","9618.63","PUNTO"],
                ["sanfernando2","7","2024-06-27","0134","5","0083 (DEBITO)","42728.25","PUNTO"],
                ["sanfernando2","7","2024-06-27","0134","5","0359 (DEBITO)","32906.34","PUNTO"],
                ["sanfernando2","7","2024-06-27","0108","3","132 (DEBITO)","48160.47","PUNTO"],
                ["sanfernando2","7","2024-06-27","0108","3","04243149807","61.85","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04144561377","18.18","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04243375962","542.06","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04144561377","29.10","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","8994","2510.22","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","006293","80.01","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","2918","70.94","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","4085","429.28","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","6295","98.23","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","827419","13751.64","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","6499","240.11","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","909208","1036.83","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","8215","14.92","Transferencia"],
                ["sanfernando2","7","2024-06-27","ZELLE","9","22577264","307.80","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","184271","98.23","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","183587","61.85","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","261671","198.27","Transferencia"],
                ["sanfernando2","7","2024-06-27","0134","5","0902","32.74","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04243375119","178.26","Transferencia"],
                ["sanfernando2","7","2024-06-27","0134","5","0778","542.06","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04243111815","451.11","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","3326","587.54","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","7658","32.74","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","0227","396.54","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","4199","2179.89","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","1708","316.51","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","1603","1484.30","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","131878","2801.26","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","768959","163.71","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04144862874","80.04","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","7016","1215.09","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","6204","25.47","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04260484929","660.30","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04127284632","1109.59","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04263239728","394.72","Transferencia"],
                ["sanfernando2","7","2024-06-27","0191","7","202073","16.37","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","9036","16.73","Transferencia"],
                ["sanfernando2","7","2024-06-27","ZELLE","9","143","142.15","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04261271020","160.07","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","6319","289.58","Transferencia"],
                ["sanfernando2","7","2024-06-27","0102","2","5908","1200.54","Transferencia"],
                ["sanfernando2","7","2024-06-27","0108","3","04269388449","201.18","Transferencia"],
                ["sanfernando2","7","2024-06-28","0134","5","0238 (DEBITO)","63076.28","PUNTO"],
                ["sanfernando2","7","2024-06-28","0134","5","0238 (CREDITO)","853.00","PUNTO"],
                ["sanfernando2","7","2024-06-28","0134","5","0360 (DEBITO)","30606.73","PUNTO"],
                ["sanfernando2","7","2024-06-28","0134","5","0084 (DEBITO)","94081.55","PUNTO"],
                ["sanfernando2","7","2024-06-28","0108","3","133 (DEBITO)","40586.18","PUNTO"],
                ["sanfernando2","7","2024-06-28","0102","2","227299","91.02","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04122943879","65.54","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","160369","32.77","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","7513","1157.84","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","311176","200.25","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04124828805","429.64","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","8285","873.48","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243699981","717.28","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04166463413","651.74","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243699981","349.54","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","987307","80.10","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","6695","1380.67","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","844242","125614.50","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243163396","32.04","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04164336253","11713.10","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","584083","80.10","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04164336253","364.10","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","318731","921.17","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","597099","1168.76","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04141444182","2800.29","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","388326","309.48","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","653505","54.61","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","0640","1085.02","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","930898","32.77","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243219749","249.41","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04145869692","2173.68","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243358475","691.79","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","2242","204.26","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","1526","65.54","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243002511","433.28","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04149406207","280.36","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","5299","29.13","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04125739036","294.92","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04127237735","1092.30","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","6275","764.61","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04145638543","2765.34","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243244370","112.87","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","2486","123.79","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243261442","1201.53","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04165117504","1345.35","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04243261442","14.56","Transferencia"],
                ["sanfernando2","7","2024-06-28","0108","3","04127448101","110.32","Transferencia"],
                ["sanfernando2","7","2024-06-28","ZELLE","9","300","300.00","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","7802","709.99","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","302467","5300.00","Transferencia"],
                ["sanfernando2","7","2024-06-28","0102","2","304465","1071.40","Transferencia"],
                ["sanfernando2","7","2024-06-29","0134","5","0361 (DEBITO)","38366.86","PUNTO"],
                ["sanfernando2","7","2024-06-29","0134","5","0239 (DEBITO)","57376.80","PUNTO"],
                ["sanfernando2","7","2024-06-29","0134","5","0239 (CREDITO)","456.13","PUNTO"],
                ["sanfernando2","7","2024-06-29","0108","3","134 (DEBITO)","55524.90","PUNTO"],
                ["sanfernando2","7","2024-06-29","0134","5","0085 (DEBITO)","56166.41","PUNTO"],
                ["sanfernando2","7","2024-06-29","0134","5","0085 (CREDITO)","360.46","PUNTO"],
                ["sanfernando2","7","2024-06-29","0108","3","04140394687","19297.99","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","508684","615.33","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","551684","411.43","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","9680","1809.58","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","7023","1627.53","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","885953","1183.32","Transferencia"],
                ["sanfernando2","7","2024-06-29","ZELLE","9","919","50.53","Transferencia"],
                ["sanfernando2","7","2024-06-29","0134","5","014286","13435.29","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","231611","433.28","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","2992","4734.20","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","4173","728.00","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04166463413","422.36","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","427312","71.00","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","6355","364.10","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","1810","58.26","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","751955","251.23","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243328252","902.97","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243416038","400.51","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243328152","502.46","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","920288","43.69","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243416038","378.66","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","1477","163.12","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","037401","2175.50","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","073425","127.43","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","1241","214.82","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","221257","731.84","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243371578","517.02","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","367046","254.87","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04143927717","12889.14","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04143927717","36.00","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243596088","64.45","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243596088","12.74","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243002511","178.41","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","541879","57600.00","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","208920","30.95","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","9894","3458.95","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","8466","101.95","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04243396719","822.87","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","584764","12080.00","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","0127","80.10","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","9027","87.75","Transferencia"],
                ["sanfernando2","7","2024-06-29","0108","3","04265300755","54.61","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","5955","3833.97","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","4825","348.08","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","326296","69.18","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","804934","841.07","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","969710","367.74","Transferencia"],
                ["sanfernando2","7","2024-06-29","0102","2","1147","4332.79","Transferencia"],
                ["sanfernando2","7","2024-06-30","0134","5","0362 (DEBITO)","4967.31","PUNTO"],
                ["sanfernando2","7","2024-06-30","0134","5","0086 (DEBITO)","7289.79","PUNTO"],
                ["sanfernando2","7","2024-06-30","0134","5","0240 (DEBITO)","25679.58","PUNTO"],
                ["sanfernando2","7","2024-06-30","0108","3","135 (DEBITO)","12100.71","PUNTO"],
                ["sanfernando2","7","2024-06-30","0102","2","547579","946.66","Transferencia"],
                ["sanfernando2","7","2024-06-30","0108","3","04249222648","10.19","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","439040","251.23","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","848107","163.84","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","876384","174.77","Transferencia"],
                ["sanfernando2","7","2024-06-30","0108","3","04144877584","47.33","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","021312","245.77","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","7072","349.90","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","221271","227.56","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","0155","10.92","Transferencia"],
                ["sanfernando2","7","2024-06-30","0102","2","9968","491.53","Transferencia"],
                ["sanfernando2","7","2024-07-01","0134","5","0087 (DEBITO)","40712.19","PUNTO"],
                ["sanfernando2","7","2024-07-01","0134","5","0087 (CREDITO)","229.64","PUNTO"],
                ["sanfernando2","7","2024-07-01","0108","3","136 (DEBITO)","57804.24","PUNTO"],
                ["sanfernando2","7","2024-07-01","0134","5","0363 (DEBITO)","47125.12","PUNTO"],
                ["sanfernando2","7","2024-07-01","0134","5","0241 (DEBITO)","95852.94","PUNTO"],
                ["sanfernando2","7","2024-07-01","0134","5","0241 (CREDITO)","726.00","PUNTO"],
                ["sanfernando2","7","2024-07-01","0102","2","107324","209.59","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","8308","656.10","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","5608","4920.64","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04127780711","189.54","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04144675111","109.35","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04141448818","1272.11","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04141448818","451.98","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04262479670","7253.55","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04269436854","100.60","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04144561377","102.06","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","897119","893.03","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","467098","357.21","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","5053","8748.40","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","488542","9076.05","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","508901","4738.68","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","642659","51.03","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","8228","49.21","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","8040","4399.52","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","9473","780.03","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","6052","200.48","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","738105","871.15","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","544909","255.88","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04243612293","34.63","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","725529","124.80","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","3354","6524.55","Transferencia"],
                ["sanfernando2","7","2024-07-01","0102","2","9199","18179.44","Transferencia"],
                ["sanfernando2","7","2024-07-01","0108","3","04144561377","1020.60","Transferencia"],
                ["sanfernando2","7","2024-07-02","0134","5","0243 (CREDITO)","543.25","PUNTO"],
                ["sanfernando2","7","2024-07-02","0134","5","0243 (DEBITO)","63505.15","PUNTO"],
                ["sanfernando2","7","2024-07-02","0134","5","0242 (DEBITO)","16681.62","PUNTO"],
                ["sanfernando2","7","2024-07-02","0108","3","137 (DEBITO)","36029.57","PUNTO"],
                ["sanfernando2","7","2024-07-02","0134","5","0364 (DEBITO)","35893.20","PUNTO"],
                ["sanfernando2","7","2024-07-02","0134","5","0088 (DEBITO)","62538.78","PUNTO"],
                ["sanfernando2","7","2024-07-02","0134","5","0088 (CREDITO)","350.02","PUNTO"],
                ["sanfernando2","7","2024-07-02","0102","2","313632","83.83","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","987145","71.10","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","1811","2282.40","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","572561","612.53","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","702322","1676.70","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04120785225","875.04","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04243612293","63.80","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","922619","692.74","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","124800","54.69","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04261112077","218.76","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04141474173","466.56","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","238235","5.83","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","972697","16698.68","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","265373","25.52","Transferencia"],
                ["sanfernando2","7","2024-07-02","0134","5","787523","3547.56","Transferencia"],
                ["sanfernando2","7","2024-07-02","0134","5","796383","87.50","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","8344","8386.02","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","8416","7350.34","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04125885382","6009.96","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04125885388","90.00","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","1988","657.00","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","0869","30.00","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04127721516","251.57","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","744167","702.58","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","910555","142.19","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","8738","69.27","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","7187","156.78","Transferencia"],
                ["sanfernando2","7","2024-07-02","ZELLE","9","9761","10.00","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04266533483","62.00","Transferencia"],
                ["sanfernando2","7","2024-07-02","0108","3","04144665406","182.30","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","9786","138.55","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","336653","153.13","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","353126","30.63","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","443590","426.58","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","1822","61.98","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","1878","1075.57","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","737927","278.19","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","2607","2763.60","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","2457","470.33","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","4876","920.62","Transferencia"],
                ["sanfernando2","7","2024-07-02","0102","2","140741","251.57","Transferencia"],
                ["sanfernando3","15","2024-06-27","0134","5","00023 (DEBITO)","24700.68","PUNTO"],
                ["sanfernando3","15","2024-06-27","0134","5","0084 (DEBITO)","14887.21","PUNTO"],
                ["sanfernando3","15","2024-06-27","0134","5","0023 (DEBITO)","18780.32","PUNTO"],
                ["sanfernando3","15","2024-06-27","0134","5","0023 (CREDITO)","1229.65","PUNTO"],
                ["sanfernando3","15","2024-06-27","0134","5","0250 (DEBITO)","42066.99","PUNTO"],
                ["sanfernando3","15","2024-06-27","0108","3","04243595458","110.96","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","371722","72.76","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","450232","65.48","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","540000","107.32","Transferencia"],
                ["sanfernando3","15","2024-06-27","0108","3","04145638845","403.82","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","735715","251.02","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","036603","164.44","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","200990","1637.10","Transferencia"],
                ["sanfernando3","15","2024-06-27","0108","3","04243534990","1015.00","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","303368","163.71","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","336092","70.94","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","558023","127.33","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","609080","211.00","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","430160","207.37","Transferencia"],
                ["sanfernando3","15","2024-06-27","0102","2","948223","36.38","Transferencia"],
                ["sanfernando3","15","2024-06-28","0134","5","0024 (DEBITO)","39353.96","PUNTO"],
                ["sanfernando3","15","2024-06-28","0134","5","000251 (DEBITO)","18282.93","PUNTO"],
                ["sanfernando3","15","2024-06-28","0134","5","00024 (DEBITO)","31307.10","PUNTO"],
                ["sanfernando3","15","2024-06-28","0134","5","0085 (DEBITO)","4584.58","PUNTO"],
                ["sanfernando3","15","2024-06-28","0102","2","014198","163.80","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","147569","744.38","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","195401","320.32","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","252343","203.84","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","109547","76.44","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","415663","2618.98","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04243518274","32.76","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04243534990","273.00","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","780954","58.24","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","844667","374.92","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","022593","13431.60","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04269414946","29.12","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","017706","80.08","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","040116","123.76","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04144887565","34.58","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","584236","91.00","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","371500","74.62","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","403024","764.40","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","149083","502.32","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","526886","105.20","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","623419","393.12","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","366738","112.84","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","578303","429.52","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04264625310","251.16","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04124241370","728.00","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","192830","1659.84","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04144756787","760.76","Transferencia"],
                ["sanfernando3","15","2024-06-28","0102","2","239884","249.92","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04127789686","214.76","Transferencia"],
                ["sanfernando3","15","2024-06-28","0108","3","04164171526","309.40","Transferencia"],
                ["sanfernando3","15","2024-06-29","0134","5","0086 (DEBITO)","53605.82","PUNTO"],
                ["sanfernando3","15","2024-06-29","0134","5","0025 (DEBITO)","23549.18","PUNTO"],
                ["sanfernando3","15","2024-06-29","0134","5","0025 (CREDITO)","415.42","PUNTO"],
                ["sanfernando3","15","2024-06-29","0134","5","000252 (DEBITO)","31615.79","PUNTO"],
                ["sanfernando3","15","2024-06-29","0134","5","000252 (CREDITO)","896.24","PUNTO"],
                ["sanfernando3","15","2024-06-29","0102","2","114010","80.17","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","818692","170.54","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","452823","185.84","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","473274","81.99","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","598048","634.06","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","812071","36.44","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","694080","619.48","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","839862","253.26","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","892496","287.88","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","966614","637.70","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","960483","91.10","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","036710","178.56","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","070736","80.17","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","105215","211.35","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","008084","80.17","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","377213","18.15","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","489579","142.12","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","501640","207.71","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","971911","102.03","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","019938","87.46","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","587277","76.52","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","166755","244.15","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","124772","47.37","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","169677","823.54","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","722498","58.30","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","172642","80.17","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","945211","130.46","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","220746","215.00","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","104570","29.15","Transferencia"],
                ["sanfernando3","15","2024-06-29","0108","3","04165435488","878.20","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","244351","163.98","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","313013","360.76","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","188321","71.06","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","505876","182.20","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","575386","1093.20","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","598172","102.03","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","766933","251.44","Transferencia"],
                ["sanfernando3","15","2024-06-29","0108","3","04164302697","83.81","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","184877","451.49","Transferencia"],
                ["sanfernando3","15","2024-06-29","0108","3","04244603733","911.00","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","580655","145.40","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","202147","473.72","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","230536","692.36","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","355518","542.96","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","407805","728.80","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","404686","119.89","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","459953","1822.00","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","698672","1275.40","Transferencia"],
                ["sanfernando3","15","2024-06-29","0102","2","749016","65.59","Transferencia"],
                ["sanfernando3","15","2024-06-29","0108","3","04165435488","1851.15","Transferencia"],
                ["sanfernando3","15","2024-06-29","0108","3","04165435488","692.36","Transferencia"],
                ["sanfernando3","15","2024-06-30","0134","5","0087 (DEBITO)","6503.83","PUNTO"],
                ["sanfernando3","15","2024-06-30","0134","5","000253 (DEBITO)","11188.24","PUNTO"],
                ["sanfernando3","15","2024-06-30","0134","5","0026 (DEBITO)","11295.34","PUNTO"],
                ["sanfernando3","15","2024-06-30","0134","5","0026 (DEBITO)","6904.29","PUNTO"],
                ["sanfernando3","15","2024-06-30","0102","2","255637","36.44","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","258412","262.37","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","637150","18.22","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","672571","105.68","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","936978","1202.52","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","954468","123.90","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","998718","673.41","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","022245","801.68","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","653151","18.22","Transferencia"],
                ["sanfernando3","15","2024-06-30","0108","3","04243595458","472.63","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","476095","51.02","Transferencia"],
                ["sanfernando3","15","2024-06-30","0102","2","047444","94.74","Transferencia"],
                ["sanfernando3","15","2024-07-01","0134","5","0088 (DEBITO)","31661.28","PUNTO"],
                ["sanfernando3","15","2024-07-01","0134","5","0088 (CREDITO)","182.20","PUNTO"],
                ["sanfernando3","15","2024-07-01","0134","5","000254 (DEBITO)","35550.91","PUNTO"],
                ["sanfernando3","15","2024-07-01","0134","5","000254 (CREDITO)","429.99","PUNTO"],
                ["sanfernando3","15","2024-07-01","0134","5","0027 (DEBITO)","122970.89","PUNTO"],
                ["sanfernando3","15","2024-07-01","0134","5","0027 (DEBITO)","21470.13","PUNTO"],
                ["sanfernando3","15","2024-07-01","0102","2","640600","437.28","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","664987","1985.98","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","668520","183.84","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","736814","1435.74","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","080458","251.44","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","386168","377.15","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","405091","368.04","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","471470","2514.36","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","701670","32.80","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","164294","34.55","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","173884","240.00","Transferencia"],
                ["sanfernando3","15","2024-07-01","ZELLE","9","5915","30.00","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","233268","129.36","Transferencia"],
                ["sanfernando3","15","2024-07-01","0108","3","04243534990","27.33","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","365932","132.28","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","560001","477.36","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","213522","63.77","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","726281","69.24","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","432399","192.77","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","363269","130.00","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","607167","127.54","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","062168","360.76","Transferencia"],
                ["sanfernando3","15","2024-07-01","0102","2","320220","728.80","Transferencia"],
                ["sanfernando3","15","2024-07-02","0134","5","000255 (DEBITO)","21544.67","PUNTO"],
                ["sanfernando3","15","2024-07-02","0134","5","0028 (DEBITO)","15526.10","PUNTO"],
                ["sanfernando3","15","2024-07-02","0134","5","0089 (DEBITO)","54582.59","PUNTO"],
                ["sanfernando3","15","2024-07-02","0134","5","0028 (DEBITO)","30168.82","PUNTO"],
                ["sanfernando3","15","2024-07-02","0134","5","0028 (CREDITO)","334.98","PUNTO"],
                ["sanfernando3","15","2024-07-02","0102","2","289618","1206.16","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","478909","980.24","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","554177","4627.88","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","612087","109.32","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","967625","433.64","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","834905","262.37","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","177473","619.65","Transferencia"],
                ["sanfernando3","15","2024-07-02","0108","3","04140502882","13851.51","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","472212","151.23","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","510291","40.10","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","774319","397.31","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","848482","122.11","Transferencia"],
                ["sanfernando3","15","2024-07-02","ZELLE","9","1406","42.80","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","913144","251.51","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","090511","71.06","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","256359","236.86","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","949420","324.41","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","382156","91.10","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","081279","433.76","Transferencia"],
                ["sanfernando3","15","2024-07-02","0102","2","197232","251.44","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0134","5","000233 (DEBITO)","850.42","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0134","5","000233 (CREDITO)","58593.54","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0134","5","000233 (CREDITO)","3078.81","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0134","5","000234 (CREDITO)","683.95","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0151","8","000058 (CREDITO)","72.40","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0151","8","000102 (DEBITO)","56477.43","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0151","8","000058 (CREDITO)","163.71","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0191","7","0374 (DEBITO)","36674.12","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0191","7","0374 (CREDITO)","771.69","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0191","7","000247 (CREDITO)","33.00","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0191","7","000247 (DEBITO)","52263.35","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0114","12","000190 (DEBITO)","26556.22","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0151","8","000122 (CREDITO)","2475.86","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0151","8","000122 (CREDITO)","651.32","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243357107","127.33","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04141464648","15.43","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","8622","283.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","041204574821","574.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243716486","112.78","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04241481659","360.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","4414","4397.13","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04120362905","269.21","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","4243716486","411.09","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","4008","203.73","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0134","5","9859","22883.02","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04144600941","1396.99","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","5415","32.74","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243150726","3565.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243150726","349.25","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04144784733","272.49","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04242107049","251.02","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243715988","254.66","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04124607388","1968.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04124607388","440.20","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04124607388","1418.82","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04161104950","134.61","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","4284","691.22","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","103717","2679.75","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","6316","65.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04145867693","2095.49","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","1917","811.27","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04127258026","181.90","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04145624904","160.07","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04127732472","7672.54","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","8789","243.75","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04127837123","465.66","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243647554","1273.30","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04145908305","163.71","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","8997","218.28","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04128606093","40.82","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","2232","6512.02","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04125381271","251.02","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","3197","111.18","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243865680","432.92","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04243040742","24.74","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","9562","69.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0102","2","1052","87.31","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-27","0108","3","04265302490","272.85","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0191","7","0375 (CREDITO)","11196.64","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0191","7","0375 (DEBITO)","44656.71","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0134","5","0235 (DEBITO)","2849.66","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0134","5","0235 (CREDITO)","426.00","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0134","5","0235 (DEBITO)","22560.85","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0151","8","000059 (CREDITO)","152.88","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0151","8","000103 (DEBITO)","28480.13","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0151","8","000191 (DEBITO)","35511.61","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0191","7","0248 (DEBITO)","77383.85","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243299917","654.84","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04241329231","87.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04161104950","2210.09","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","2765","309.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243716486","80.08","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","4369","131.04","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","7312","9427.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04120421964","9245.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","1056","542.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","8447","465.92","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04124087486","3476.20","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","0144","178.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243489057","134.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04120421964","582.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04121422193","178.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","5307","1062.88","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04263452846","91.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04120345459","43.68","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04164395659","60.06","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04143928059","666.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243391366","72.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04143227794","498.68","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243285811","323.96","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04140397593","65.52","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04124233442","626.08","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04122389690","9063.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","9940","627.90","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","7717","910.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","2842","131.04","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04144507496","200.20","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243373985","578.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243373985","211.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04123677902","724.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","9949","108.84","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04128814810","141.96","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243545622","396.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","0458","322.14","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04126763411","54.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","8357","43.68","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04142965687","651.56","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04124525896","6151.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243569766","218.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","8915","12.01","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04266316061","218.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","0808","80.08","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0108","3","04243371270","305.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-28","0102","2","8476","145.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0191","7","0249 (DEBITO)","124287.80","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0134","5","0236 (DEBITO)","13544.42","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0134","5","0236 (CREDITO)","46375.95","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0151","8","000192 (DEBITO)","34175.02","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0151","8","000192 (CREDITO)","309.42","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0151","8","000060 (CREDITO)","283.92","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0151","8","000104 (DEBITO)","32707.20","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0191","7","0376 (DEBITO)","38699.41","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04123911086","163.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04125511285","72.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04141474242","214.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243661655","87.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04265330147","214.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04123826419","298.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243620315","116.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243327244","1430.52","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04127587356","1646.37","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04120178995","196.56","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","7745","182.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","3180","54.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","2866","174.72","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","3243","91.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04120366633","192.92","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243271293","364.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04145437961","1543.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","1562","778.96","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04124512074","262.08","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243584717","393.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04124407740","70.98","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04248433663","305.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","0685","465.92","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04129402956","141.96","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04144552554","775.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","3284","680.68","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","7822","101.92","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04141492758","16.74","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04124344657","91.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04123677893","123.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","2091","4040.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243284777","54.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","4296","276.64","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","6038","462.28","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","0690","251.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04122723656","759.67","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0102","2","6638","40.77","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04143466053","4877.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04124351347","433.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243183941","54.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04124166602","422.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243185256","3000.09","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04128307889","91.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243258506","5423.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04243269154","1638.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-29","0108","3","04125889768","942.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0134","5","0237 (DEBITO)","69.16","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0134","5","0237 (CREDITO)","1798.16","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0134","5","0237 (DEBITO)","38908.92","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0151","8","000060 (CREDITO)","283.92","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0151","8","000104 (DEBITO)","32707.20","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0191","7","0251 (DEBITO)","76.44","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0191","7","0251 (CREDITO)","19103.48","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0151","8","000192 (DEBITO)","34175.02","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0151","8","000192 (CREDITO)","309.42","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0191","7","0377 (DEBITO)","7681.02","PUNTO"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243605312","160.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04121384182","9828.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","53743","2901.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","2606","61.88","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04168462673","273.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04124448600","414.96","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04124707422","160.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04124528505","422.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04120459806","1274.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04121490736","80.66","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243811114","47.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243201249","72.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243638521","578.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","0934","72.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04125126807","1820.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243011236","58.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04120380427","138.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243672391","76.44","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","3688","91.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04123826419","32.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04124324605","542.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04143922148","203.84","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04143922148","396.76","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","3049","5456.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04248433663","159.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04143922886","29.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04144743522","16.38","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243683206","163.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04166439050","50.60","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04266316061","112.84","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04123826419","25.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0102","2","9710","309.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-06-30","0108","3","04243740070","21.84","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0151","8","000194 (DEBITO)","49537.63","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0151","8","000124 (CREDITO)","550.34","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0151","8","000124 (CREDITO)","288.99","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0151","8","000106 (DEBITO)","19759.55","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0151","8","000062 (CREDITO)","688.72","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0191","7","0378 (CREDITO)","948.54","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0191","7","0378 (DEBITO)","40809.24","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0191","7","0252 (CREDITO)","160.70","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0191","7","0252 (CREDITO)","2089.11","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0191","7","0252 (DEBITO)","78676.64","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04143922148","307.58","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04129256989","170.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04247316916","72.88","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243688162","482.83","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04141977433","25.51","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243777032","36.26","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04142249305","239.05","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04242088298","1289.98","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","8075","5429.56","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243502497","463.72","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04122095140","142.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243250699","215.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04167353576","65.59","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04128605716","1421.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04128605716","7105.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243385509","1402.94","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04127592815","18.22","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04120344137","429.99","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04128605716","692.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04123752486","47.37","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04127541978","200.42","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","2513","94.74","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","045120988525","9.11","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04120439785","579.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04127611618","200.42","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04125128913","138.47","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04144910927","36.44","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04140475331","251.44","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04242415580","313.88","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04121001853","437.28","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04142941544","911.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04269414946","273.30","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04120403734","29.15","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04121506688","72.88","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","ZELLE","9","9050","95.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04127451366","430.43","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","6112","32.80","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04121068549","1275.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04124245874","109.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04124391250","167.62","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04124391250","18.22","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04129845218","142.12","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","3928","484.65","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","ZELLE","9","4461","47.50","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04128606993","36.44","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04121089994","102.03","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","3589","4336.36","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04149456924","6377.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","5224","69.24","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0102","2","3644","397.20","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04120346429","204.06","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","0413342006","772.53","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243563920","211.35","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04120459806","223.01","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04145901223","539.31","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-01","0108","3","04243051761","575.75","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0151","8","000195 (DEBITO)","43937.93","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0191","7","000252 (DEBITO)","215.15","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0151","8","000125 (CREDITO)","641.56","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0151","8","000125 (CREDITO)","772.60","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0151","8","000107 (DEBITO)","32035.42","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0191","7","0379 (DEBITO)","14985.39","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0191","7","0253 (CREDITO)","328.05","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0191","7","0253 (CREDITO)","926.00","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0191","7","0253 (DEBITO)","139317.69","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0134","5","0238 (DEBITO)","264.26","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0134","5","0238 (DEBITO)","654.18","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0134","5","0238 (CREDITO)","431.18","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0134","5","0238 (CREDITO)","73890.31","PUNTO"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04124944897","72.90","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04144519523","393.66","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","ZELLE","9","7036","249.30","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04244369348","510.30","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243586952","69.26","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","9099","29.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","3805","43.74","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04128896345","10.95","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","0383","1315.85","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04162401041","92.95","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243019737","65.61","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","87603","5431.05","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04143342006","389.83","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04143342006","208.13","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04120183737","80.19","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243243838","3010.41","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","2490","499.37","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04129325763","7829.46","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","8688","87.48","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04266418845","703.49","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04127554804","178.61","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","53530","324.41","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243480714","4802.65","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04141462073","462.81","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04122954666","692.55","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04143931764","171.32","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243582714","21.87","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","ZELLE","9","4181","139.00","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","7105","236.93","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04241412292","105.70","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04127339617","297.94","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04127775882","51.03","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04120842461","251.51","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","7892","5463.86","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243428830","543.11","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04129925748","65.61","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0105","4","1523","561.33","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","2846","102.06","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04144686718","189.54","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04142266267","335.34","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","7499","346.28","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","5563","69.25","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04120377575","1352.30","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04129184893","182.25","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04125934158","309.83","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04125126807","770.55","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04243067587","142.16","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04140513044","138.51","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04122730929","969.57","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","57859","437.40","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0102","2","2158","5431.05","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04144674287","284.31","Transferencia"],
                ["sanjuandelosmorros","11","2024-07-02","0108","3","04260345006","107.53","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0134","5","0052 (DEBITO)","89088.06","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0134","5","0054 (DEBITO)","71124.96","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0134","5","0054 (CREDITO)","216.46","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0134","5","0054 (DEBITO)","19892.44","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0151","8","0098 (DEBITO)","78296.91","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0151","8","0045 (CREDITO)","1990.48","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243378635","502.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243142684","429.28","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243178132","65.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04123456934","7967.22","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144552554","40.02","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243596734","7152.31","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04143461093","1029.55","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0102","2","757543","110.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144443922","1107.77","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04123673429","90.95","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04143661749","25.47","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243124447","143.70","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04123491447","309.23","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04129251983","1900.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04127771255","98.23","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0102","2","079324","127.33","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04121446713","251.02","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04264448853","374.71","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243053148","29.10","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243053148","65.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0102","2","4620","309.23","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243543643","574.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243408793","170.62","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04149474217","50.93","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04123657782","45.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144003647","5118.67","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04241814701","141.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243764914","1003.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04167472938","23.65","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243167457","50.93","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243021463","101.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","3144180770","1271.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04124811902","1000.09","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0102","2","7556","218.28","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243557751","276.49","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243260660","2182.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243369068","1608.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04121073346","14534.17","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04123657782","509.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243856365","723.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144654595","232.83","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144653595","30.92","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04249488607","127.33","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04249488607","90.95","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243537499","138.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243557751","69.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04145438839","403.82","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04120365087","2786.71","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04120365087","18.19","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04147857120","6366.50","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04120587211","596.63","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04149478091","80.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04149478091","10.91","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243051761","25.47","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04243569655","101.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-27","0108","3","04144556487","251.02","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","0053 (CREDITO)","1922.50","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","0053 (DEBITO)","39280.36","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","055 (DEBITO)","93588.49","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","055 (CREDITO)","73.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0151","8","0099 (DEBITO)","85773.03","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0151","8","0046 (CREDITO)","271.20","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","0055 (CREDITO)","4266.08","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0134","5","0055 (DEBITO)","12755.04","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243071895","294.84","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","997017","163.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","829220","178.36","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","468197","436.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243757853","8.01","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","6563","771.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","8537","305.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","1337","43.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243718702","1186.64","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","9916","29.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","5151","496.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04144443922","160.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","992781","4695.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","0027","141.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243528862","1255.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","057611","152.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04124587557","672.67","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","1058","120.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04144348255","138.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","0992","1452.36","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","290461","222.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04143928079","127.40","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","2748","118.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","1488","371.28","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","879466","50.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","0209","2366.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243661466","329.42","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","702656","1250.34","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04241661782","1270.36","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04128606223","69.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04162354156","4600.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04269414946","1551.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","6608","149.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","147963","273.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04143409980","291.20","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04242312792","251.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04124523493","140.14","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04122147169","236.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","6185","356.72","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04123598331","1445.08","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04127761721","87.36","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04140499831","6229.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04268321422","4695.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04143409980","138.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04263410290","331.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243296815","400.40","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243661466","131.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243195451","283.92","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243107388","262.08","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04124882012","389.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04144542432","2002.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04123414337","36.40","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0102","2","0565","141.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04243015174","138.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-28","0108","3","04124364762","1292.20","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0056 (DEBITO)","86366.39","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0056 (CREDITO)","963.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0056 (DEBITO)","11879.44","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0056 (CREDITO)","38542.96","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0054 (CREDITO)","70.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0134","5","0054 (DEBITO)","67373.34","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0151","8","0100 (DEBITO)","73777.51","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0151","8","0047 (CREDITO)","291.52","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243463706","1100.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0102","2","9757","29.15","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04127761721","499.23","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124521405","1155.15","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124521405","378.98","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243443459","65.59","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243443459","18.22","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04162448979","218.64","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04144931285","826.82","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04145978335","266.01","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04142381123","6256.75","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04128666070","120.25","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","ZELLE","9","9889881","14.75","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243442228","10.93","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124753003","83.81","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243779307","71.06","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04260349809","131.18","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04140513044","120.25","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04140513044","87.46","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124895535","874.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04241620937","6296.83","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04123428098","402.66","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","ZELLE","9","18617467","11.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04144754640","72.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04141369675","12999.97","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04144472662","5800.15","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124662345","6522.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243548796","142.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243575030","10312.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124404781","224.83","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0102","2","7160","6522.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04127000454","145.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04143921470","131.18","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04120366995","726.98","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04242018184","488.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243330538","874.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0102","2","3520","870.92","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243493442","276.94","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04124539531","67.41","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04125922834","72.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243503211","5826.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04141464648","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0102","2","4685","229.57","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04125836747","411.77","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04143921470","211.35","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04248433663","239.05","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04161438960","7251.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04160960365","397.20","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243048043","320.67","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243299917","3607.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04128049105","72.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243278261","36.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243802255","615.84","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243802255","58.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243346837","32.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04122930210","7251.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04122930210","72.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04144443922","1202.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243508554","249.98","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04149185390","3385.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243346837","36.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-29","0108","3","04243027081","80.46","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0134","5","0057","30509.98","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0134","5","0057","252.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0134","5","0055","182.20","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0134","5","0055","24684.92","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0151","8","0101","17777.41","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0151","8","0048","540.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0134","5","0057","11465.88","PUNTO"],
                ["sanjuandelosmorros2","16","2024-06-30","0102","2","8289","389.91","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04241974891","729.35","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243201249","604.90","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243657049","29.15","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04241783085","36.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04121750805","185.84","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243166759","896.42","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04144934755","36.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243110970","71.06","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04123811088","98.39","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243717962","451.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243020547","105.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04128858148","794.39","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04122663471","787.10","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04124601983","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243362647","4008.40","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243362647","105.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04124669137","65.59","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243362647","700.01","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04242607269","6377.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243036258","4942.72","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04144529590","287.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243043302","429.99","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04124448600","455.50","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04241701392","231.39","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243480081","415.42","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04243140680","2254.54","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0102","2","9711","142.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04128670685","102.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04128670685","142.12","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04248433663","116.61","Transferencia"],
                ["sanjuandelosmorros2","16","2024-06-30","0108","3","04129014187","5065.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0151","8","0102 (DEBITO)","48652.89","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0058 (DEBITO)","67383.73","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0056 (CREDITO)","1543.42","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0056 (DEBITO)","73316.02","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0058 (DEBITO)","19869.40","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0059 (CREDITO)","5060.15","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0134","5","0059 (CREDITO)","39475.42","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128861957","87.46","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128861957","43.73","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04121401516","203.70","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04147967167","102.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243516870","1902.17","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04127592815","189.49","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","0374","827.19","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","7688","127.18","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243517513","276.94","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","ZELLE","9","12842055","21.70","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04127837578","298.81","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243254214","58.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04144490815","112.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128840188","2963.67","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243059198","1013.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04140537433","47.37","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04127592367","295.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","9375","8499.63","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04242116958","178.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243054077","138.47","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04125015543","251.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04129208040","550.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04144497512","83.81","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243399993","273.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04163657531","11693.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243448557","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04163657531","867.27","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243448709","339.99","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04141442133","313.38","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124749524","11660.84","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04127855291","80.17","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04127855291","546.60","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128606341","138.47","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04145982746","112.78","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243065379","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124313378","43.73","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04143952242","1956.83","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243271212","903.71","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243336646","54.66","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243726292","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243620292","470.08","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04241206519","472.63","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243557751","690.54","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04248295341","266.01","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04149492175","69.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128964188","1709.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243755448","18.22","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124083037","29.15","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04123826419","318.85","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","3187","1202.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04128964188","247.79","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04144542432","364.40","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04165435477","36.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124528695","1803.78","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243038792","37.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04122030496","289.70","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243759805","448.21","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","0272","787.10","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124097134","91.10","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124388603","6377.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243747325","728.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04144784733","380.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04124667192","32.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04121104426","401.93","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04129347093","801.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0102","2","9873","652.28","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04243037622","182.20","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04144737474","138.47","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04120364616","652.28","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-01","0108","3","04145799747","22447.04","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0060 (CREDITO)","143.16","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0060 (DEBITO)","99011.57","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0151","8","0103 (DEBITO)","64124.93","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0057 (CREDITO)","371.79","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0057 (DEBITO)","18958.29","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0059 (DEBITO)","42368.65","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0134","5","0059 (CREDITO)","486.00","PUNTO"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","4016","353.56","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243638521","761.81","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243035217","280.67","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243035217","131.22","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04145583749","72.90","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04128647494","149.44","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04149028374","171.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04120344263","196.83","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04266316061","189.54","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","8788","10935.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243285677","999.82","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243734799","65.61","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04242171549","72.90","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04145901223","65.61","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","2074","284.31","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04129312509","273.38","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243646934","5431.05","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243254214","408.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243755163","5463.86","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243755163","58.32","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243468054","637.88","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04143921470","102.06","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243318743","320.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","8523","142.16","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04144542809","1211.96","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04123617125","32.81","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04123151542","266.09","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04123151542","164.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04124900409","1544.39","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04242460125","5431.05","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04124527037","211.41","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243507398","14725.80","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243661466","25.52","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243507398","364.50","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04124777046","725.36","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","4144261092","1760.54","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04129161515","18.23","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04144756687","21.87","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04144561608","430.11","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04127303785","1900.14","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04143342006","87.48","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04125480916","652.46","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04163272488","6378.75","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243636587","3645.00","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","0412506377","6003.31","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","0883","54.68","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04144946873","182.25","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243048509","408.24","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243759805","331.69","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243240785","707.13","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243285846","164.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04120460691","65.61","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243775512","36.45","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","5869","7800.30","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0102","2","2725","433.76","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04164428578","461.09","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243651127","10133.10","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04144596877","10.94","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04149478091","495.72","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243198143","51.03","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243759805","488.43","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04124627547","2739.95","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04243134573","364.50","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04124627547","275.38","Transferencia"],
                ["sanjuandelosmorros2","16","2024-07-02","0108","3","04125370975","102.06","Transferencia"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (CREDITO)","451.11","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (DEBITO)","29350.39","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (DEBITO)","38563.47","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (DEBITO)","34522.19","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (CREDITO)","6864.23","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (CREDITO)","1494.50","PUNTO"],
                ["tinaquillo","18","2024-06-27","0134","5","0005 (DEBITO)","54269.80","PUNTO"],
                ["tinaquillo","18","2024-06-27","0102","2","67654","48276.26","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04127445708","90.95","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04244069207","47.29","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04124696484","360.16","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04124696484","363.80","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04121420519","251.02","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","04244473664","1418.82","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","04144273189","80.04","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","0105","1418.82","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","0871","251.02","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","04127809970","20.01","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","77567","702.13","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","7980","213.73","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","04123468154","651.20","Transferencia"],
                ["tinaquillo","18","2024-06-27","0108","3","04267762048","36.38","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","9934","72.76","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","0492","25.47","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","6834","356.52","Transferencia"],
                ["tinaquillo","18","2024-06-27","ZELLE","9","6940","200.00","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","06197","1087.76","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","29974","3965.42","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","06080","207.37","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","67629","727.60","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","60166","2208.27","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","80029","727.60","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","0512","363.80","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","02109","2670.29","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","14624","80.04","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","05762","130.97","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","643001","2080.57","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","8959","360.16","Transferencia"],
                ["tinaquillo","18","2024-06-27","0102","2","4808","76.40","Transferencia"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (DEBITO)","93763.47","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (CREDITO)","1081.08","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (CREDITO)","106.00","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (DEBITO)","43557.79","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (CREDITO)","976.08","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (DEBITO)","44636.12","PUNTO"],
                ["tinaquillo","18","2024-06-28","0134","5","0006 (DEBITO)","33175.79","PUNTO"],
                ["tinaquillo","18","2024-06-28","0102","2","6231","1048.32","Transferencia"],
                ["tinaquillo","18","2024-06-28","ZELLE","9","4266","64.90","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04244921220","320.32","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","0325","21.84","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","704228","3239.60","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","0684","1281.28","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","3138","742.20","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","5316","462.28","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","7878","538.36","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","000862","724.36","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","2040","629.72","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","010051","1790.88","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","0966","223.96","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","2336","80.00","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","7558","902.72","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","4360","120.12","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","4057","81.90","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","7912","5.52","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","2610","60.00","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","6043","527.80","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","6222","1059.24","Transferencia"],
                ["tinaquillo","18","2024-06-28","ZELLE","9","0000","149.00","Transferencia"],
                ["tinaquillo","18","2024-06-28","ZELLE","9","0000","1.00","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","63655","160.16","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04127430466","80.08","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04127430466","25.48","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04244024297","465.92","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","0414330411","251.16","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","5997","323.96","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04121378632","578.76","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04167365190","2111.20","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04121378632","21.84","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04144853458","360.36","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04124557969","1967.42","Transferencia"],
                ["tinaquillo","18","2024-06-28","0134","5","8898","17468.36","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","5451","647.92","Transferencia"],
                ["tinaquillo","18","2024-06-28","0102","2","57784","69.16","Transferencia"],
                ["tinaquillo","18","2024-06-28","ZELLE","9","4295","17.80","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04141420677","10883.60","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04144965119","218.40","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04160884289","247.52","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04169187906","1088.36","Transferencia"],
                ["tinaquillo","18","2024-06-28","0108","3","04244167346","209.30","Transferencia"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (DEBITO)","42200.37","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (CREDITO)","429.99","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (DEBITO)","121362.06","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (CREDITO)","16783.90","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (DEBITO)","41328.31","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (CREDITO)","801.68","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (DEBITO)","63918.26","PUNTO"],
                ["tinaquillo","18","2024-06-29","0134","5","0007 (CREDITO)","215.00","PUNTO"],
                ["tinaquillo","18","2024-06-29","0108","3","04161884710","14688.96","Transferencia"],
                ["tinaquillo","18","2024-06-29","ZELLE","9","9264","13.00","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04124420615","593.97","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04144352479","98.39","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04125240324","298.81","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04125240324","415.42","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","03956","7980.36","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04124032184","4700.76","Transferencia"],
                ["tinaquillo","18","2024-06-29","ZELLE","9","8944","169.00","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04129554561","2514.36","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04128482560","153.05","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04128706139","443.84","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04164412050","712.04","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04167767270","324.32","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04122216461","102.03","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04160200586","215.00","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04121429900","182.20","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","5678","167.62","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","0239","357.11","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","3933","535.67","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04244012826","54.66","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04123550258","127.54","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04120430816","4700.76","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04124870608","69.24","Transferencia"],
                ["tinaquillo","18","2024-06-29","ZELLE","9","5367","30.00","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04129558017","215.00","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04125240324","262.37","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04244055456","1421.16","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04140389763","706.94","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04124344367","389.91","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04144066952","25.51","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04244506797","69.24","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","8951","641.34","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","1852","102.03","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04261920141","52.84","Transferencia"],
                ["tinaquillo","18","2024-06-29","0102","2","9259","91.10","Transferencia"],
                ["tinaquillo","18","2024-06-29","0108","3","04261920141","69.24","Transferencia"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (DEBITO)","12275.78","PUNTO"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (CREDITO)","12754.08","PUNTO"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (DEBITO)","16217.73","PUNTO"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (DEBITO)","13831.78","PUNTO"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (DEBITO)","14980.89","PUNTO"],
                ["tinaquillo","18","2024-06-30","0134","5","0008 (CREDITO)","1547.00","PUNTO"],
                ["tinaquillo","18","2024-06-30","0102","2","0297","140.29","Transferencia"],
                ["tinaquillo","18","2024-06-30","0108","3","04160200586","526.56","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","4039","692.36","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","022503","45.55","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","2910","182.20","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","5545","251.44","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","1626","174.91","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","008980","127.54","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","6925","167.62","Transferencia"],
                ["tinaquillo","18","2024-06-30","0108","3","04124300446","200.42","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","438972","756.13","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","65403","180.38","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","8971","80.17","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","7587","127.54","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","2351","1089.56","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","187848","182.20","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","6966","502.87","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","781296","364.40","Transferencia"],
                ["tinaquillo","18","2024-06-30","0102","2","2615","145.76","Transferencia"],
                ["tinaquillo","18","2024-07-01","0134","5","009 (CREDITO)","390.00","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","009 (DEBITO)","67497.61","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (DEBITO)","168.00","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (CREDITO)","41905.99","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (DEBITO)","29486.23","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (CREDITO)","2465.17","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (CREDITO)","225.93","PUNTO"],
                ["tinaquillo","18","2024-07-01","0134","5","0009 (DEBITO)","32216.93","PUNTO"],
                ["tinaquillo","18","2024-07-01","0102","2","9512","51.02","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04127805392","896.42","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124783607","911.00","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","8567","127.54","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124476358","249.00","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124476358","24.05","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04140389763","1479.46","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","7468","502.87","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124935199","302.27","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124935199","204.06","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04124296213","200.42","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04129555039","258.72","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04127602223","167.62","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","2907","1089.56","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","041479420355","178.56","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04127805392","204.06","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04149420355","575.75","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04123219775","112.96","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","4916","1191.59","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","0624","349.82","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","1701","235.04","Transferencia"],
                ["tinaquillo","18","2024-07-01","0108","3","04243665075","54.66","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","8658","1464.89","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","3332","62.68","Transferencia"],
                ["tinaquillo","18","2024-07-01","0102","2","4661","357.11","Transferencia"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (DEBITO)","33672.99","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (CREDITO)","153.09","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (DEBITO)","119843.40","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (DEBITO)","51759.20","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (CREDITO)","681.62","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (CREDITO)","215.06","PUNTO"],
                ["tinaquillo","18","2024-07-02","0134","5","0010 (DEBITO)","51890.28","PUNTO"],
                ["tinaquillo","18","2024-07-02","0102","2","0392","80.19","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","0501","193.19","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","1009","127.58","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","2117","1159.11","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","6279","60.14","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","1283","105.70","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","6798","134.87","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","4187","6889.05","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","0066","335.34","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","2427","127.58","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","2618","503.01","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","301931","102.06","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","5239","6889.05","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","4142","218.70","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","5048","91.13","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","28280","346.28","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","14321","8164.80","Transferencia"],
                ["tinaquillo","18","2024-07-02","0134","5","14286","31675.05","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","4784","178.61","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","435150","1299.81","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","239035","136.69","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","5851","320.76","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","3414","102.06","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","1835","9.11","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","2904","940.41","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","2890","133.04","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","0059","1953.72","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","8357","1403.33","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","8373","215.06","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","4429","430.11","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","9633","20.05","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","8661","34.63","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","1420","597.78","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","6388","1089.86","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","9691","1818.86","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","6874","6889.05","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","0552","470.21","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","8964","160.38","Transferencia"],
                ["tinaquillo","18","2024-07-02","0102","2","7425","6160.05","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","072 (DEBITO)","48009.96","PUNTO"],
                ["valledelapascua1","9","2024-06-27","0108","3","333 (DEBITO)","101396.76","PUNTO"],
                ["valledelapascua1","9","2024-06-27","0134","5","000107 (DEBITO)","69823.50","PUNTO"],
                ["valledelapascua1","9","2024-06-27","0108","3","04160245758","69.08","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","8679","727.20","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","4789","3090.75","Transferencia"],
                ["valledelapascua1","9","2024-06-27","ZELLE","9","5942","558.90","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243037039","170.89","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243280313","181.80","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04129671181","502.04","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","6473","83.63","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243655692","425.41","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04248569536","5417.64","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04167902712","250.88","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04129761408","25.00","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","1514","209.19","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04120117415","1596.20","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04124466990","109.08","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04124522940","69.12","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04126477160","105.50","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","2428","105.50","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","1693","178.16","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","1968","2692.12","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04245558922","254.66","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243492885","90.95","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04248478888","163.62","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04144759020","63.67","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243037039","69.08","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243272074","1415.18","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243097649","250.88","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","6375","978.08","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243620760","214.64","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04124658793","236.47","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04124658793","174.62","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04143856846","1361.68","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243406904","74.54","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04125380847","698.50","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0102","2","1042","181.90","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243046253","181.90","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243339111","356.33","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04243608104","505.68","Transferencia"],
                ["valledelapascua1","9","2024-06-27","0108","3","04266582045","1434.46","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0134","5","000108 (DEBITO)","57071.56","PUNTO"],
                ["valledelapascua1","9","2024-06-28","0108","3","073 (DEBITO)","57552.96","PUNTO"],
                ["valledelapascua1","9","2024-06-28","0108","3","334 (DEBITO)","71034.82","PUNTO"],
                ["valledelapascua1","9","2024-06-28","0108","3","04144631491","90.24","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04121996005","687.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04243037039","69.16","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04121308111","72.44","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04142960188","509.60","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","5184","469.56","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","2535","36.76","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","2212","46.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04127964788","138.32","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04144359771","58.24","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04127559400","3603.60","Transferencia"],
                ["valledelapascua1","9","2024-06-28","ZELLE","9","24791162","431.70","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04243652711","16.31","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04160232521","247.52","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04121495304","232.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","3678","243.88","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","7420","323.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","ZELLE","9","20957774","35.00","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","7976","724.36","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04122974578","69.16","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04144580405","251.16","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04144902916","109.20","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04125125557","840.84","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04141495129","630.81","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","2874","687.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","5255","72.80","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04125990634","120.12","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","0218","101.92","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","5673","220.22","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","5139","273.00","Transferencia"],
                ["valledelapascua1","9","2024-06-28","ZELLE","9","18264309","76.00","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04127453080","65.52","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04127453080","116.48","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","0930","76.44","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","1066","1405.04","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0102","2","4402","728.00","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04125099501","414.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04127775508","141.96","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04124650823","360.36","Transferencia"],
                ["valledelapascua1","9","2024-06-28","0108","3","04124650823","542.36","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04261928940","469.56","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04121993975","251.16","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04143464586","364.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","9228","4422.60","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04120369923","98.28","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04120824038","14.56","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","7254","127.40","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04264388726","32.76","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","2493","72.80","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04263694865","542.36","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","5472","2438.07","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","9385","4331.60","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243272268","247.52","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243490609","302.12","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04163304205","622.44","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","2960","182.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04124440322","29.12","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","0932","182.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243571438","33.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","5909","6370.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243092359","127.40","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","9538","364.00","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04124522940","14.56","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","3838","290.84","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","8069","47.32","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243610365","171.08","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","6522","112.84","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","6456","331.24","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04248406057","249.34","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0102","2","3800","833.56","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0108","3","04243281199","269.36","Transferencia"],
                ["valledelapascua1","9","2024-06-29","0134","5","000109","49169.87","PUNTO"],
                ["valledelapascua1","9","2024-06-29","0108","3","335","69943.27","PUNTO"],
                ["valledelapascua1","9","2024-06-29","0108","3","074","44227.26","PUNTO"],
                ["valledelapascua1","9","2024-06-30","0134","5","000110 (DEBITO)","10429.70","PUNTO"],
                ["valledelapascua1","9","2024-06-30","0191","7","072 (DEBITO)","25536.22","PUNTO"],
                ["valledelapascua1","9","2024-06-30","0108","3","336 (DEBITO)","50099.72","PUNTO"],
                ["valledelapascua1","9","2024-06-30","0108","3","04261444299","775.32","Transferencia"],
                ["valledelapascua1","9","2024-06-30","0108","3","04162412442","811.72","Transferencia"],
                ["valledelapascua1","9","2024-06-30","0108","3","04144469825","644.28","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0134","5","000111","73846.22","PUNTO"],
                ["valledelapascua1","9","2024-07-01","0108","3","076","51582.88","PUNTO"],
                ["valledelapascua1","9","2024-07-01","0108","3","337","65949.50","PUNTO"],
                ["valledelapascua1","9","2024-07-01","0108","3","04128304054","127.54","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0102","2","4254","7251.56","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04143464062","131.18","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124612852","200.42","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124612852","983.88","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04161350996","32.80","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04243169215","226.66","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04129473800","69.24","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0102","2","4065","419.06","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04121305802","287.88","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04128809381","251.44","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124132988","349.82","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0102","2","6734","433.64","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04243371626","178.56","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04142961410","240.50","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04243789394","47.37","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124074789","823.54","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0102","2","9498","8344.76","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04243553154","83.81","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04121019344","32.80","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04121332614","18.22","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04248274508","109.32","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04145626359","5280.16","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04128383914","5429.56","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04129761936","69.24","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0102","2","5746","506.52","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04243382327","284.23","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04261148651","80.17","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124618154","564.82","Transferencia"],
                ["valledelapascua1","9","2024-07-01","0108","3","04124525101","204.06","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","077 (DEBITO)","64533.90","PUNTO"],
                ["valledelapascua1","9","2024-07-02","0108","3","338 (DEBITO)","129820.53","PUNTO"],
                ["valledelapascua1","9","2024-07-02","0134","5","000112 (DEBITO)","78565.72","PUNTO"],
                ["valledelapascua1","9","2024-07-02","0102","2","9017","364.40","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4731","1713.15","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4760","273.38","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","8222","668.86","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04141256432","1880.82","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243231624","36.45","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04145169543","42.28","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","9057","958.64","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","1008","1421.55","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","3677","1126.31","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4702","2515.05","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243989888","6378.75","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243710209","200.48","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243716209","65.61","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243484992","484.79","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","9746","178.61","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243700483","659.75","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243700483","69.25","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04125929720","1020.60","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4215","874.80","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","9759","207.77","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243759480","1038.83","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04262483106","255.15","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04164323380","6524.55","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04160601604","324.41","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04165460504","22.78","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","8673","215.06","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243358838","28.29","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04129761405","200.48","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04120159212","82.01","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","2488","127.58","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","1038","980.50","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","3336","69.25","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04127598428","255.15","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","8917","72.90","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4343","6524.55","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","0264","2515.05","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243565789","69.25","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243693898","1275.75","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04263698116","211.41","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243693898","9.84","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04243693898","464.01","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04144477721","200.48","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","1179","14.58","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0108","3","04122340289","69.25","Transferencia"],
                ["valledelapascua1","9","2024-07-02","0102","2","4724","397.31","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0134","5","000191 (CREDITO)","16822.11","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000191 (DEBITO)","40450.13","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000195 (DEBITO)","119224.87","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000195 (CREDITO)","211.00","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000197 (DEBITO)","49152.40","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000193 (DEBITO)","61387.86","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0134","5","000193 (CREDITO)","600.27","PUNTO"],
                ["valledelapascua2","10","2024-06-27","0108","3","04124654683","207.37","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","0468","502.04","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","2146","345.61","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0175","6","6614","188720.67","Transferencia"],
                ["valledelapascua2","10","2024-06-27","ZELLE","9","16045514","86.90","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","0639","58.21","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0175","6","9528","251.02","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","6270","9276.90","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","6270","296.50","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","5875","145.52","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0175","6","3731","8174.59","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","7934","87.31","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","2606","582.08","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","9146","120.00","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","4946","978.62","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","3434","181.90","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","6081","1069.57","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","2473","294.68","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","3031","472.03","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","6760","309.23","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","6607","534.79","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","0176","141.88","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04243620760","945.92","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04140526966","1200.54","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04243255409","163.71","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","5282","432.92","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","4660","189.18","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","8860","174.62","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0105","4","3191","32.74","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04268440090","544.97","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","8435","218.28","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04144491167","221.92","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04261423915","287.40","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0108","3","04243684999","181.90","Transferencia"],
                ["valledelapascua2","10","2024-06-27","0102","2","9487","470.03","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04160840535","6370.00","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","9365","979.16","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","7535","98.28","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","3285","418.60","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","0957","7534.80","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","0333","40.04","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04144351325","815.36","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04243860250","564.20","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04161442275","1399.58","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","9132","240.24","Transferencia"],
                ["valledelapascua2","10","2024-06-28","ZELLE","9","10984157","89.00","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","0293","1404.31","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","1193","331.24","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","7742","287.20","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","3100","214.76","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","6368","3913.00","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","0667","334.88","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","3296","764.40","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04128964285","360.36","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04144654616","265.72","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04243581064","3494.40","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","7741","61.88","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","6383","18.20","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","3821","98.28","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04124937508","382.20","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","8372","407.68","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","5084","433.16","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","7969","91.00","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04144780929","105.56","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","7511","1015.56","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","1493","4695.60","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04128304054","262.08","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","5393","141.96","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04163370320","101.92","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04143926013","310.67","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0102","2","5765","142.32","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04121413190","163.80","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04243503831","80.08","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04124863075","72.80","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04124680538","4695.60","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04243300477","502.32","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04128533701","200.20","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04262362789","185.64","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04144870826","138.32","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0108","3","04124137022","944.94","Transferencia"],
                ["valledelapascua2","10","2024-06-28","0134","5","000196","98809.56","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000196","449.54","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000192","1223.64","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000192","45884.92","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000194","48785.16","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000194","1230.32","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000198","51425.92","PUNTO"],
                ["valledelapascua2","10","2024-06-28","0134","5","000198","222.04","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000195 (DEBITO)","64416.71","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000193 (CREDITO)","59.00","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000193 (DEBITO)","28137.75","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000197 (CREDITO)","14770.28","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000197 (DEBITO)","75668.16","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000199 (DEBITO)","33875.21","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0134","5","000199 (CREDITO)","61.88","PUNTO"],
                ["valledelapascua2","10","2024-06-29","0102","2","5224","125.58","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04144351325","396.58","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04243254036","469.56","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04122934938","182.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","2325","502.32","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","3110","546.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04144438807","50.78","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04121303403","54.60","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04243093516","364.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","0059","120.12","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04128480048","48.59","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","2453","40.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","8678","360.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04243402335","47.32","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","0740","928.20","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","9568","29.12","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","1336","245.70","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04264392535","382.20","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","7231","979.16","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04269446350","243.88","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04128371937","251.16","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","3298","937.30","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04129662796","247.52","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04124925958","141.96","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04124616537","276.64","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04124925958","297.02","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","8661","888.52","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04243087544","163.80","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","8874","433.16","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04129423526","54.60","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04126760635","567.84","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04124934838","251.16","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","1428","65.52","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","1922","1001.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","5916","36.40","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04168770943","160.16","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","5888","80.95","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04129673706","4025.80","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","9691","536.90","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","4270","979.12","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","4331","214.76","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","3990","61.88","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04128838717","851.76","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","8780","182.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04127774175","30.94","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","7103","738.92","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","8812","287.56","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0108","3","04265308748","43.68","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","9308","185.64","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","9436","72.80","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","5189","14560.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","4413","273.00","Transferencia"],
                ["valledelapascua2","10","2024-06-29","0102","2","6612","178.36","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0134","5","000196 (DEBITO)","24199.09","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000196 (CREDITO)","160.16","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000200 (DEBITO)","27087.94","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000200 (CREDITO)","131.04","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000194 (CREDITO)","364.00","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000194 (DEBITO)","7293.37","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000198 (DEBITO)","31591.01","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0134","5","000198 (CREDITO)","469.56","PUNTO"],
                ["valledelapascua2","10","2024-06-30","0102","2","2489","105.56","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0102","2","1681","945.31","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0102","2","1520","407.68","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04129678350","1634.36","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0102","2","3507","535.81","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0102","2","4312","105.56","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04128647461","9.28","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04124618309","227.50","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04121974834","251.16","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04123409206","127.40","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04144438807","45.50","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04144469825","21.84","Transferencia"],
                ["valledelapascua2","10","2024-06-30","0108","3","04243540496","109.20","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0134","5","000195 (DEBITO)","54474.96","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000197 (DEBITO)","69731.29","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000197 (CREDITO)","5938.64","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000201 (DEBITO)","58097.22","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000201 (CREDITO)","612.19","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000199 (DEBITO)","93343.76","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0134","5","000199 (CREDITO)","327.96","PUNTO"],
                ["valledelapascua2","10","2024-07-01","0108","3","04128472741","19069.05","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04243111295","1042.18","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04128860873","47.37","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04143442029","943.80","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04243480895","47.37","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","1367","94.74","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","4081","1670.77","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04122670059","222.28","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","6817","215.00","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04264495473","4700.76","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","7826","7251.56","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","7379","27.33","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04124931021","502.87","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","4416","911.00","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04125533540","6.05","Transferencia"],
                ["valledelapascua2","10","2024-07-01","ZELLE","9","9604060","139.00","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","0059","506.52","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04142961410","1614.29","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","1530","1953.18","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","1658","794.39","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","9036","45.55","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","5046","13610.34","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","8416","346.18","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","5206","420.88","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04124775722","105.31","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","7141","7282.17","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","5755","3641.08","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","2712","364.40","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","2542","138.47","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04243632223","98.39","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04144528537","5065.16","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","0270","47.37","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","2255","142.12","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04129423526","30.00","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","2652","542.96","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04243093530","1053.12","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04243093530","1421.16","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04144496223","58.30","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04124612852","122.44","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","2036","102.03","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","8254","688.72","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","9770","269.66","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","9726","5429.56","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04141445684","451.86","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04122155100","138.47","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","3693","320.67","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0102","2","9796","138.47","Transferencia"],
                ["valledelapascua2","10","2024-07-01","0108","3","04261467464","934.69","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0134","5","000202 (DEBITO)","38630.49","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000202 (CREDITO)","54.68","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000198 (DEBITO)","50493.54","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000198 (CREDITO)","1802.46","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000200 (DEBITO)","45649.77","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000200 (CREDITO)","488.43","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000196 (DEBITO)","30699.76","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0134","5","000196 (CREDITO)","5690.57","PUNTO"],
                ["valledelapascua2","10","2024-07-02","0102","2","1391","348.46","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1744","6823.44","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","6138","88.39","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","9199","251.51","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","0136","506.66","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","7706","211.41","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1765","65.61","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","6211","36.45","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04145843824","76.00","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04141475122","251.51","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","4990","69.25","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","4463","69.25","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04124356904","1751.79","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","5428","377.26","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","3135","178.61","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","6724","145.80","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","8394","233.28","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","2133","91.13","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04147693513","503.01","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04124937432","6524.55","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","9721","309.83","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04127440999","721.71","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04243118155","422.82","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","5059","80.19","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","6657","251.51","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","5090","207.77","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1177","590.49","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1748","80.19","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","9673","52.85","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04162326892","7800.30","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04243107579","178.61","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1427","371.06","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","2987","4000.00","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04144005674","357.21","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1641","1567.78","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04127465788","52.85","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","7424","506.66","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","1429","1261.17","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","7868","4337.55","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","4307","178.61","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0108","3","04124650492","430.11","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","6158","911.25","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","5964","218.70","Transferencia"],
                ["valledelapascua2","10","2024-07-02","0102","2","9820","7446.74","Transferencia"],
        ];
 


       /*  $arr = [
            ["guacara", "14","2024-07-01",  "0134",    "5",   "0114",	"43081.16",	"PUNTO"],
            ["guacara", "14","2024-07-01",  "0134",    "5",   "00116",	"143.03",	"PUNTO"],
            ["guacara", "14","2024-07-01",  "0134",    "5",   "0114",	"696.01",	"PUNTO"],
            ["sanfernando1", "6",    "2024-06-29",  "0134",    "5",   "0237 (DEBITO)",	"70246.12",	"PUNTO"],
            ["sanfernando3", "15",    "2024-06-30",  "0134",    "5",   "0026 (DEBITO)",	"11295.34",	"PUNTO"],
            ["sanfernando3", "15",    "2024-07-01",  "0134",    "5",   "0027 (DEBITO)",	"122970.89",	"PUNTO"],
            ["sanfernando3", "15",    "2024-07-02",  "0134",    "5",   "0028 (DEBITO)",	"15526.10",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-06-27",  "0134",    "5",   "000233 (CREDITO)",	"58593.54",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-06-27",  "0151",    "8",   "000122 (CREDITO)",	"2475.86",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-06-28",  "0134",    "5",   "0235 (DEBITO)",	"2849.66",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-01",  "0151",    "8",   "000124 (CREDITO)",	"550.34",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-01",  "0191",    "7",   "0252 (CREDITO)",	"160.70",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-02",  "0151",    "8",   "000125 (CREDITO)",	"641.56",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-02",  "0191",    "7",   "0253 (CREDITO)",	"328.05",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-02",  "0134",    "5",   "0238 (DEBITO)",	"264.26",	"PUNTO"],
            ["sanjuandelosmorros", "11",  "2024-07-02",  "0134",    "5",   "0238 (CREDITO)",	"431.18",	"PUNTO"],
            ["sanjuandelosmorros2", "16", "2024-06-27",  "0134",    "5",   "0054 (DEBITO)",	"71124.96",	"PUNTO"],
            ["sanjuandelosmorros2", "16", "2024-06-30",  "0134",    "5",   "0057",	"30509.98",	"PUNTO"],
            ["sanjuandelosmorros2", "16", "2024-07-01",  "0134",    "5",   "0058 (DEBITO)",	"67383.73",	"PUNTO"],
            ["sanjuandelosmorros2", "16", "2024-07-01",  "0134",    "5",   "0059 (CREDITO)",	"5060.15",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-27",  "0134",    "5",   "0005 (DEBITO)",	"29350.39",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-27",  "0134",    "5",   "0005 (DEBITO)",	"38563.47",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-27",  "0134",    "5",   "0005 (DEBITO)",	"34522.19",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-27",  "0134",    "5",   "0005 (CREDITO)",	"6864.23",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-30",  "0134",    "5",   "0008 (DEBITO)",	"12275.78",	"PUNTO"],
            ["tinaquillo", "18",  "2024-06-30",  "0134",    "5",   "0008 (CREDITO)",	"12754.08",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-01",  "0134",    "5",   "0009 (DEBITO)",	"168.00",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-01",  "0134",    "5",   "0009 (CREDITO)",	"41905.99",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-01",  "0134",    "5",   "0009 (DEBITO)",	"29486.23",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-01",  "0134",    "5",   "0009 (CREDITO)",	"2465.17",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-02",  "0134",    "5",   "0010 (DEBITO)",	"33672.99",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-02",  "0134",    "5",   "0010 (CREDITO)",	"153.09",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-02",  "0134",    "5",   "0010 (DEBITO)",	"119843.40",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-02",  "0134",    "5",   "0010 (DEBITO)",	"51759.20",	"PUNTO"],
            ["tinaquillo", "18",  "2024-07-02",  "0134",    "5",   "0010 (CREDITO)",	"681.62",	"PUNTO"],
        ];  */

        /* $arr =[["elorza", "1",  "2024-06-29",  "0134",    "5",   "004 (DEBITO)",	"22371.84",	"PUNTO"],
        ["elorza", "1",  "2024-06-30",  "0134",    "5",   "005 (DEBITO)",	"7285.69",	"PUNTO"],
        ["elorza", "1",  "2024-06-28",  "0134",    "5",   "003 (DEBITO)",	"11081.78",	"PUNTO"],
        ["elsaman", "4",  "2024-06-28",  "0134",    "5",   "0221 (DEBITO)",	"5331.87",	"PUNTO"],]; */


        foreach ($arr as $key => $e) {
            $id_origen = $e[1];
            $fecha = $e[2];
            $banco = $e[3];
            $id_banco = $e[4];
            $lote = $e[5];
            $monto = $e[6];
            $tipo = $e[7];

            $cat = $tipo=="PUNTO"?"DEBITO":null;
            
           /*  $check = puntosybiopagos::where("fecha",$fecha)
            ->where("tipo",$tipo)
            ->where("loteserial",$lote." EXTRAIDO")
            ->where("id_banco",$id_banco)
            ->where("monto",$monto)
            ->where("banco",$banco)
            ->whereNotNull("monto_liquidado")
            ->first(); */

            if ($tipo=="Transferencia") {
              /*  puntosybiopagos::updateOrCreate([
                   "id_usuario" => 1,
                   "fecha" => $fecha,
                   "id_sucursal" => $id_origen,
                   "tipo" => $tipo,
                   "loteserial" => $lote,
                   "monto" => $monto,
                   "banco" => $banco,
                   "id_banco" => $id_banco,
                   "debito_credito" => $cat,
                   "created_at" => "2024-08-28 12:59:59"

               ], [
                   "id_usuario" => 1,
                   "fecha" => $fecha,
                   "id_sucursal" => $id_origen,
                   "tipo" => $tipo,
                   "loteserial" => $lote,
                   "monto" => $monto,
                   "banco" => $banco,
                   "id_banco" => $id_banco,
                   "debito_credito" => $cat,
                    //"fecha_liquidacion" => null,
                   //"monto_liquidado" => null, 
                   "created_at" => "2024-08-28 12:59:59"
               ]);   */
            } 


        } 
    }
}
