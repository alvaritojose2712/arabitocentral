<?php

namespace App\Http\Controllers;

use App\Models\bancos;
use App\Http\Requests\StorebancosRequest;
use App\Http\Requests\UpdatebancosRequest;
use App\Models\bancos_list;
use App\Models\cuentasporpagar;
use App\Models\puntosybiopagos;
use Illuminate\Http\Request;

class BancosController extends Controller
{
    function getBancosData(Request $req) {

        $qdescripcionbancosdata = $req->qdescripcionbancosdata;
        $qbancobancosdata = $req->bancoSelectAuditoria;
        
        $qfechabancosdata = $req->fechaSelectAuditoria;
        $fechaHastaSelectAuditoria = $req->fechaHastaSelectAuditoria;
        $sucursalSelectAuditoria = $req->sucursalSelectAuditoria;
        $subviewAuditoria = $req->subviewAuditoria;
        $columnOrder = $req->orderColumnAuditoria;
        $order = $req->orderAuditoria;
        

        if (!$qfechabancosdata OR !$fechaHastaSelectAuditoria) {
            return "Fechas de Búsqueda en Blanco";
        }

        $puntosybiopagos = puntosybiopagos::with("sucursal")->when($qbancobancosdata!="",function($q) use ($qbancobancosdata) {
            $q->whereIn("banco",bancos_list::where("id",$qbancobancosdata)->select("codigo"));
        })
        ->when($subviewAuditoria=="liquidar",function($q) {
            $q->whereNull("fecha_liquidacion");
        })
        ->when($qdescripcionbancosdata!="",function($q) use($qdescripcionbancosdata) {
            $q->orwhere("loteserial",$qdescripcionbancosdata)
            ->orwhere("monto",$qdescripcionbancosdata)
            ->orwhere("monto_liquidado",$qdescripcionbancosdata);
        })
        ->when($qfechabancosdata!="",function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria, $subviewAuditoria) {
            if ($subviewAuditoria=="cuadre" || $subviewAuditoria=="conciliacion") {
                $field = "fecha_liquidacion";
            }else if ($subviewAuditoria=="liquidar") {
                $field = "fecha";
            }
            $q->whereBetween($field, [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
        })
        ->when($sucursalSelectAuditoria!="",function($q) use ($sucursalSelectAuditoria) {
            $q->orwhere("id_sucursal",$sucursalSelectAuditoria);
        })
        ->orderBy($columnOrder,$order);




        $cuenta = cuentasporpagar::with("sucursal")->where("metodo","<>","EFECTIVO")
        ->when($qbancobancosdata!="",function($q) use ($qbancobancosdata) {
            $q->whereIn("metodo",bancos_list::where("id",$qbancobancosdata)->select("codigo"));
        })
        ->when($qdescripcionbancosdata!="",function($q) use($qdescripcionbancosdata) {
            $q->orwhere("numfact",$qdescripcionbancosdata)
            ->orwhere("monto",$qdescripcionbancosdata);
        })
        ->when($qfechabancosdata!="",function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria) {
            $q->whereBetween("fechaemision", [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
        })
        ->when($sucursalSelectAuditoria!="",function($q) use ($sucursalSelectAuditoria) {
            $q->orwhere("id_sucursal",$sucursalSelectAuditoria);
        })
        ->get()
        ->map(function ($q) {
            $q->loteserial = $q->numfact;
            $q->tipo = "Transferencia";
            $q->categoria = 2;
            $q->fecha = $q->fechaemision;
            $q->fecha_liquidacion = $q->fechaemision;
            $q->monto_liquidado = $q->monto;
            $q->id_usuario = $q->id;
            $q->banco = $q->metodo;

            return $q;
        });
        
        
        $bancosSum = [];

        $puntosmascuentas = array_merge($puntosybiopagos->get()->toArray(), $cuenta->toArray());
        array_multisort(array_column($puntosmascuentas, $columnOrder), $order=="desc"? SORT_DESC: SORT_ASC, $puntosmascuentas);
        
        $xbanco = collect($puntosmascuentas)->map(function ($q) {
            if ($q["tipo"]=="PUNTO 1" OR $q["tipo"]=="PUNTO 2") {
                $q["tipo"] = "PUNTO";
            }
            return $q;
        })
        ->groupBy("banco");

        foreach ($xbanco as $i => $e) {

            $bancosSum[$i] = [
                
                "ingreso" => [
                    "monto" => $e->where("monto",">",0)->sum("monto"),
                    "monto_liquidado" => $e->where("monto_liquidado",">",0)->sum("monto_liquidado"), 
                ],
                "egreso" => [
                    "monto" => $e->where("monto","<",0)->sum("monto"),
                    "monto_liquidado" => $e->where("monto_liquidado","<",0)->sum("monto_liquidado"), 
                ],
            ];

            foreach ($e->where("monto","<",0)->groupBy("tipo") as $tipoIndex => $tipos) {
                $bancosSum[$i]["egreso"][$tipoIndex] = [
                    "monto" => $tipos->sum("monto"),
                    "monto_liquidado" => $tipos->sum("monto_liquidado"),
                    "movimientos" => $tipos,
                ];
            }

            foreach ($e->where("monto",">",0)->groupBy("tipo") as $tipoIndex => $tipos) {
                $bancosSum[$i]["ingreso"][$tipoIndex] = [
                    "monto" => $tipos->sum("monto"),
                    "monto_liquidado" => $tipos->sum("monto_liquidado"),
                    "movimientos" => $tipos,
                ];
            }
        }

        $xfechaCuadreArr = collect($puntosmascuentas)->groupBy(["fecha_liquidacion","banco"]);
        $xfechaCuadre = [];
        foreach ($xfechaCuadreArr as $KeyfechasGroup => $fechasGroup) {
            foreach ($fechasGroup as $KeybancoGroup => $bancosGroup) {
                $ingresoBanco = 0;
                $egresoBanco = 0;
                foreach ($bancosGroup as $i => $e) {
                    if ($e["monto_liquidado"] < 0) {
                        $egresoBanco += $e["monto_liquidado"];
                    }else{
                        $ingresoBanco += $e["monto_liquidado"];
                    }
                }
                $q_banco = bancos::where("fecha",$KeyfechasGroup)->where("banco")->first();
                $inicial = $this->getSaldoInicialBanco($KeyfechasGroup,$KeybancoGroup);
                $balance = $ingresoBanco-$egresoBanco+$inicial;
                array_push($xfechaCuadre, [
                    "fecha" => $KeyfechasGroup,
                    "banco" => $KeybancoGroup,
                    
                    "ingreso" => $ingresoBanco,
                    "egreso" => $egresoBanco,
                    "inicial" => $inicial, 
                    "balance" => $balance, 

                    "guardado" => $q_banco, 
                ]);

            }
        }
       

        return [
            "xfechaCuadre" => $xfechaCuadre,
            "puntosybiopagosxbancos" => $bancosSum,
            "sum" => 0,
            "xliquidar" => $puntosybiopagos->get(), 
            "estado" => true,
            "view" => $subviewAuditoria,
        ];
    }
    function getSaldoInicialBanco($fecha, $banco) {
        $saldo = bancos::where("banco",$banco)->where("fecha","<",$fecha)->orderBy("fecha","desc")->first("saldo");
        if (! $saldo) {
            return 0;
        }
        return $saldo;
    }
    function sendsaldoactualbancofecha(Request $req) {  
        $banco = $req->banco;
        $fecha = $req->fecha;
        $saldo = $req->saldo;
        

        $ban = bancos::updateOrCreate(["banco"=>$banco, "fecha" => $fecha],[
            "id_usuario" => null,
            "descripcion" => null,
            "saldo" => $saldo,
        ]);

        if ($ban) {
            return [
                "estado" => true,
                "msj" => "Éxito",
            ];
        }
        return [
            "estado" => false,
            "msj" => "Err",
        ];
    }
}
