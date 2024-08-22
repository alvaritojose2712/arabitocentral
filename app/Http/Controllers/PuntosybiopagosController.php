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
            if ($comision > 0) {
                $liquidado = puntosybiopagos::find($id);
                $catcompos = catcajas::where("nombre","CAJA MATRIZ: COMISION PUNTO DE VENTA")->first();
                $comision_monto = abs($comision)*-1;
                $com = puntosybiopagos::updateOrCreate([
                    "id_origen_comision" => $id
                ],[
                    "loteserial" => $liquidado->loteserial." COMISION POS",
                    "banco" => $liquidado->banco,
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

        $all = array_filter($this->getGastosFun([
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
        ]),function ($q) {
            return $q["cat"]["catgeneral"]==2||$q["cat"]["catgeneral"]==3;
        });

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
            return Response::json(["estado" => false,"msj"=>"Error: ",$e->getMessage()]);
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
            $c = cierres::where("fecha","<=",$q->fecha)->first();
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
            
            $bs = (new CierresController)->dividir($monto_liquidado,$tasa);
            
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
}
