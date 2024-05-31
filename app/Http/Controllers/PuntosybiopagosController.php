<?php

namespace App\Http\Controllers;

use App\Models\bancos_list;
use App\Models\cajas;
use App\Models\catcajas;
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
            $cuentasPagoTipo = $req->cuentasPagoTipo;
            $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
            
            $cuentasPagosMonto = $cuentasPagoTipo=="egreso"? $req->cuentasPagosMonto*-1:$req->cuentasPagosMonto;
            $cuentasPagosMetodo = $req->cuentasPagosMetodo;
            $cuentasPagosPuntooTranfe = $req->cuentasPagosPuntooTranfe;
            $cuentasPagosSucursal = $req->cuentasPagosSucursal;
            

            
            $cuentasPagosFecha = $req->cuentasPagosFecha;

            $cuentasPagosCategoria = $req->cuentasPagosCategoria;
    
            $today = new \DateTime((new NominaController)->today());
            $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
                "nombre" => "ADMINISTRACION",
                "codigo" => "administracion",
            ]);
            $banco = bancos_list::find($cuentasPagosMetodo);
            if ($banco) {
                $mov = puntosybiopagos::updateOrCreate([
                    "id" => $id
                ],[
                    "loteserial" => $cuentasPagosDescripcion,
                    "banco" => $banco->codigo,
                    "tipo" => $cuentasPagosPuntooTranfe,

                    "fecha" => $cuentasPagosFecha,
                    "monto" => $cuentasPagosMonto,
                    "fecha_liquidacion" => $cuentasPagosPuntooTranfe=="Transferencia"? $cuentasPagosFecha:null,
                    "monto_liquidado" => $cuentasPagosPuntooTranfe=="Transferencia"? $cuentasPagosMonto:null,
                    "id_sucursal" => $cuentasPagosSucursal?$cuentasPagosSucursal: $su->id,
                    "id_usuario" => 1,
                    "categoria" => $cuentasPagosCategoria
                ]);
        
                if ($mov) {
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

        $gastosQ = "";
        $gastosQCategoria = "";
        $catgeneral = "";
        $ingreso_egreso = "";
        $typecaja = "";
        $gastosorder = "desc";
        $gastosfieldorder = "montodolar";

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
        ]);

        $distribucionGastosCat = collect($all["data"])->groupBy("categoria");
        $distribucionGastosSucursal = collect($all["data"])->groupBy("id_sucursal");

        $distribucionGastosCatMod = [
        ];
        $distribucionGastosSucursalMod = [
        ];

        
        foreach ($distribucionGastosCat as $i => $cat) {
            $sum = $cat->sum("montodolar");
            $nombre = "";
            $id = "";
            if ($cat->count()) {
                $nombre = $cat[0]["cat"]["nombre"];
                $id = $cat[0]["cat"]["id"];
            }
            if (!array_key_exists($cat[0]["cat"]["ingreso_egreso"],$distribucionGastosCatMod)) {
                $distribucionGastosCatMod[$cat[0]["cat"]["ingreso_egreso"]] = [
                    "data"=>[],
                    "sum"=>0,
                ];
            }
            array_push($distribucionGastosCatMod[$cat[0]["cat"]["ingreso_egreso"]]["data"],[
                "sum" => ($sum),
                "nombre" => $nombre,
                "id" => $id,
                "por" => 0,
            ]);
        }
        foreach ($distribucionGastosSucursal as $i => $cat) {
            $sum = $cat->sum("montodolar");
            $nombre = "";
            $id = "";
            if ($cat->count()) {
                $nombre = $cat[0]["sucursal"]["codigo"];
                $id = $cat[0]["cat"]["id"];
            }
            if (!array_key_exists($cat[0]["cat"]["ingreso_egreso"],$distribucionGastosSucursalMod)) {
                $distribucionGastosSucursalMod[$cat[0]["cat"]["ingreso_egreso"]] = [
                    "data"=>[],
                    "sum"=>0,

                ];
            }
            array_push($distribucionGastosSucursalMod[$cat[0]["cat"]["ingreso_egreso"]]["data"],[
                "sum" => ($sum),
                "nombre" => $nombre,
                "id" => $id,
                "por" => 0,
            ]);
        }

        foreach ($distribucionGastosCatMod as $key => $e) {
            $distribucionGastosCatMod[$key]["sum"] = array_sum(array_column($e["data"],"sum"));
        }
        foreach ($distribucionGastosSucursalMod as $key => $e) {
            $distribucionGastosSucursalMod[$key]["sum"] = array_sum(array_column($e["data"],"sum"));
        }

        foreach ($distribucionGastosCatMod as $key => $q) {
            $sumCatMod = $q["sum"];
            foreach ($q["data"] as $keykey => $qq) {
                $distribucionGastosCatMod[$key]["data"][$keykey]["por"] = round(($sumCatMod==0||$qq["sum"]==0?0:  (abs($qq["sum"]*100)/$sumCatMod))  ,2);
            }
        }
        foreach ($distribucionGastosSucursalMod as $key => $q) {
            $sumCatMod = $q["sum"];
            foreach ($q["data"] as $keykey => $qq) {
                $distribucionGastosSucursalMod[$key]["data"][$keykey]["por"] = round(($sumCatMod==0||$qq["sum"]==0?0:  (abs($qq["sum"]*100)/$sumCatMod))  ,2);
            }
        }
        
        return [
            "distribucionGastosCat" => $distribucionGastosCatMod,
            "distribucionGastosSucursal" => $distribucionGastosSucursalMod,
        ];
    }

    function autoliquidarTransferencia(Request $req) {
        $type = $req->type;
        $fechaSelectAuditoria = $req->fechaSelectAuditoria;
        $fechaHastaSelectAuditoria = $req->fechaHastaSelectAuditoria;

        $p = puntosybiopagos::whereBetween("fecha", [$fechaSelectAuditoria, !$fechaHastaSelectAuditoria?$fechaSelectAuditoria:$fechaHastaSelectAuditoria])
        ->where("tipo","Transferencia")
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
        $catgeneral = $arr["catgeneral"];
        $ingreso_egreso = $arr["ingreso_egreso"];
        $typecaja = $arr["typecaja"];
        $gastosorder = $arr["gastosorder"];
        $gastosfieldorder = $arr["gastosfieldorder"];

        $gastos =  cajas::with(["sucursal","cat"])
        ->when($gastosQ,function($q) use ($gastosQ){
            $q->where("concepto",$gastosQ);
        })
        ->when($gastosQFecha,function($q) use ($gastosQFecha,$gastosQFechaHasta) {
            $q->whereBetween("fecha", [$gastosQFecha, !$gastosQFechaHasta?$gastosQFecha:$gastosQFechaHasta]);
        })
        ->when($gastosQCategoria,function($q) use ($gastosQCategoria) {
            $q->where("categoria",$gastosQCategoria);
        })
        ->when($typecaja,function($q) use ($typecaja) {
            $q->where("tipo",$typecaja);
        })
        ->when($catgeneral,function($q) use ($catgeneral) {
            $q->whereIn("categoria",catcajas::where("catgeneral",$catgeneral)->select("id"));
        })
        ->when($ingreso_egreso,function($q) use ($ingreso_egreso) {
            $q->whereIn("categoria",catcajas::where("ingreso_egreso",$ingreso_egreso)->select("id"));
        })
        ->get()
        ->map(function($q) {
            $q->ingreso_egreso = $q->cat->ingreso_egreso;
            $q->catgeneral = $q->cat->catgeneral;
            $q->variable_fijo = $q->cat->variable_fijo;
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

        $catgeneral = $req->catgeneral;
        $ingreso_egreso = $req->ingreso_egreso;
        $typecaja = $req->typecaja;

        $gastosorder = $req->gastosorder;
        $gastosfieldorder = $req->gastosfieldorder;

        
        $alldata = $this->getGastosFun([
            "gastosQ" => $gastosQ,
            "gastosQFecha" => $gastosQFecha,
            "gastosQFechaHasta" => $gastosQFechaHasta,
            "gastosQCategoria" => $gastosQCategoria,
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
        
        $montoDolar = 0;
        $montoBs = 0;
        $taseBs = 0;
        $modeMoneda = $req->modeMoneda;
        if ($modeMoneda=="dolar") {
            $montoDolar = abs($gastosMonto_dolar)*-1;
        }elseif ($modeMoneda=="bs"){
            $montoBs = abs($gastosMonto)*-1;
            $taseBs = abs($gastosTasa);
        }
        $tipo = "Transferencia";
        if (strtoupper($gastosBanco)=="EFECTIVO") {
            $tipo = "EFECTIVO";
        }
        $admin_id = sucursal::updateOrCreate(["codigo"=>"administracion"],[
            "nombre" => "ADMINISTRACION",
            "codigo" => "administracion",
        ]);

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
                $id_sucursal = $admin_id->id;
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
            }
        }
        return [
            "msj" => $num." movimiento".($num<=1?"":"s")." cargado".($num<=1?"":"s"),
            "estado" => true,
        ];
    }
}
