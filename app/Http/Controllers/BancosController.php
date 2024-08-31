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



    function getCuentaAuditoria($arr) {
        $qbancobancosdata = $arr["qbancobancosdata"];
        $qdescripcionbancosdata = $arr["qdescripcionbancosdata"];
        $qfechabancosdata = $arr["qfechabancosdata"];
        $fechaHastaSelectAuditoria = $arr["fechaHastaSelectAuditoria"];
        $sucursalSelectAuditoria = $arr["sucursalSelectAuditoria"];

        return  cuentasporpagar::with("sucursal")
        ->whereNotIn("metodo",["BINANCE","AirTM","EFECTIVO"])
        ->when($qbancobancosdata!="",function($q) use ($qbancobancosdata) {
        })
        ->when($qdescripcionbancosdata!="",function($q) use ($qdescripcionbancosdata) {
            $q->orwhere("numfact",$qdescripcionbancosdata)
            ->orwhere("monto",$qdescripcionbancosdata);
        })
        ->when($qfechabancosdata!="",function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria) {
            $q->whereBetween("fechaemision", [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
        })
        ->when($sucursalSelectAuditoria!="",function($q) use ($sucursalSelectAuditoria) {
            $q->where("id_sucursal",$sucursalSelectAuditoria);
        })
        ->get()
        ->map(function($q) {
            $q->loteserial = $q->numfact;
            $q->tipo = "Transferencia";
            $q->categoria = 2;
            $q->fecha = $q->fechaemision;
            $q->fecha_liquidacion = $q->fechaemision;
            $q->id_usuario = $q->id;
            return $q;
        });
    }
    function bancosDataFun($arr) {
        $qdescripcionbancosdata = $arr["qdescripcionbancosdata"];
        $qbancobancosdata = $arr["qbancobancosdata"];
        $qfechabancosdata = $arr["qfechabancosdata"];
        $fechaHastaSelectAuditoria = $arr["fechaHastaSelectAuditoria"];
        $sucursalSelectAuditoria = $arr["sucursalSelectAuditoria"];
        $subviewAuditoria = $arr["subviewAuditoria"];
        $columnOrder = $arr["columnOrder"];
        $order = $arr["order"];
        $tipoSelectAuditoria = isset($arr["tipoSelectAuditoria"])?$arr["tipoSelectAuditoria"]:"";
        $showallSelectAuditoria = isset($arr["showallSelectAuditoria"])?$arr["showallSelectAuditoria"]:"";
        $ingegreSelectAuditoria = isset($arr["ingegreSelectAuditoria"])?$arr["ingegreSelectAuditoria"]:"";
        
        
        

        if (!$qfechabancosdata OR !$fechaHastaSelectAuditoria) {
            return "Fechas de Búsqueda en Blanco";
        }

        $puntosybiopagos = puntosybiopagos::with("sucursal")
        ->whereNotIn("banco",["EFECTIVO"])
        ->when($tipoSelectAuditoria!="",function($q) use ($tipoSelectAuditoria) {
            $q->where("tipo","LIKE",$tipoSelectAuditoria."%");
        })
        ->where("categoria","<>",66) //no considerar transferencia no reportada
        ->when($qbancobancosdata!="",function($q) use ($qbancobancosdata) {
            $q->where("id_banco",$qbancobancosdata);
        })
        ->when($subviewAuditoria=="liquidar",function($q) use ($showallSelectAuditoria) {
            if (!$showallSelectAuditoria) {
                $q->whereNull("fecha_liquidacion");
            }
            
        })
        ->when($ingegreSelectAuditoria!="",function($q) use ($ingegreSelectAuditoria) {
            if ($ingegreSelectAuditoria=="INGRESO") {
                $q->where("monto",">=",0);
            }else{
                $q->where("monto","<",0);
            }
            
        })
        
        ->when($qdescripcionbancosdata!="",function($q) use($qdescripcionbancosdata) {
            $q->orwhere("loteserial",$qdescripcionbancosdata)
            ->orwhere("monto",$qdescripcionbancosdata)
            ->orwhere("monto_liquidado",$qdescripcionbancosdata);
        })
        ->when($qfechabancosdata!="",function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria, $subviewAuditoria, $showallSelectAuditoria) {
            $field = "fecha_liquidacion";
            if ($subviewAuditoria=="cuadre" || $subviewAuditoria=="conciliacion") {
                $field = "fecha_liquidacion";
                $q->whereBetween($field, [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);

            }else if ($subviewAuditoria=="liquidar") {

                if (!$showallSelectAuditoria) {
                    $field = "fecha";
                    $q->whereBetween($field, [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
                }else{
                    $q->where(function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria, $subviewAuditoria, $showallSelectAuditoria) {
                        $q->orwhere(function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria, $subviewAuditoria, $showallSelectAuditoria) {
                            $q->whereBetween("fecha", [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
                            $q->whereNull("fecha_liquidacion");
                        })
    
                        ->orwhere(function($q) use ($qfechabancosdata, $fechaHastaSelectAuditoria, $subviewAuditoria, $showallSelectAuditoria) {
                            $q->whereBetween("fecha_liquidacion", [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria]);
                        });
                        
                    });
                }

            }

        })
        ->when($sucursalSelectAuditoria!="",function($q) use ($sucursalSelectAuditoria) {
            $q->where("id_sucursal",$sucursalSelectAuditoria);
        })
        ->orderBy($columnOrder,$order);

        $movsnoreportados = puntosybiopagos::with("sucursal")->where("categoria",66)->whereNull("fecha")
        ->whereBetween("fecha_liquidacion", [$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria])
        ->get();
        $movsnoreportadossum = $movsnoreportados->sum("monto_liquidado");
        
        $movsyareportados = puntosybiopagos::with("sucursal")->where("categoria",66)
        ->whereBetween("fecha",[$qfechabancosdata, !$fechaHastaSelectAuditoria?$qfechabancosdata:$fechaHastaSelectAuditoria])
        ->get();

        $movsnoreportadosTotal = puntosybiopagos::with("sucursal")->where("categoria",66)->whereNull("fecha")
        ->get();
        $movsnoreportadosTotalsum = $movsnoreportadosTotal->sum("monto_liquidado");



        /* return [
            $qbancobancosdata,
            $qdescripcionbancosdata,
            $qfechabancosdata,
            $sucursalSelectAuditoria,
        ]; */

        $arrq = [
            "qbancobancosdata" => $qbancobancosdata,
            "qdescripcionbancosdata" => $qdescripcionbancosdata,
            "qfechabancosdata" => $qfechabancosdata,
            "fechaHastaSelectAuditoria" => $fechaHastaSelectAuditoria,
            "sucursalSelectAuditoria" => $sucursalSelectAuditoria,
        ];
        $cuenta1 = $this->getCuentaAuditoria($arrq);
        $cuenta2 = $this->getCuentaAuditoria($arrq);
        $cuenta3 = $this->getCuentaAuditoria($arrq);
        $cuenta4 = $this->getCuentaAuditoria($arrq);
        $cuenta5 = $this->getCuentaAuditoria($arrq);

        $bancoselect = null;
        if ($qbancobancosdata) {
            $bancoselect =  $qbancobancosdata;
        }

        $bs1 = array_filter($cuenta1->map(function ($q) use ($bancoselect,$qbancobancosdata) {
            if ($qbancobancosdata) {
                if ($q->id_metodobs1==$bancoselect) {
                    
                }else{
                    return null;
                }
            }
            $q->banco = $q->metodobs1;
            $q->id_banco = $q->id_metodobs1;
            $sum = 0;
            if ($q->montobs1) {$sum += $q->montobs1;}
            $q->monto_liquidado = $sum*-1;
            $q->monto = $sum*-1;
            if ($q->metodobs1) {
                return $q;
            }
        })->toArray(),function($q) {
            return $q!==null;
        });
        $bs2 = array_filter($cuenta2->map(function ($q) use ($bancoselect,$qbancobancosdata) {
            if ($qbancobancosdata) {
                if ($q->id_metodobs2==$bancoselect) {
                    
                }else{
                    return null;
                }
            }
            $q->banco = $q->metodobs2;
            $q->id_banco = $q->id_metodobs2;
            $sum = 0;
            if ($q->montobs2) {$sum += $q->montobs2;}
            $q->monto_liquidado = $sum*-1;
            $q->monto = $sum*-1;
            if ($q->metodobs2) {
                return $q;
            }
        })->toArray(),function($q) {
            return $q!==null;
        });
        $bs3 = array_filter($cuenta3->map(function ($q) use ($bancoselect,$qbancobancosdata) {
            if ($qbancobancosdata) {
                if ($q->id_metodobs3==$bancoselect) {
                    
                }else{
                    return null;
                }
            }
            $q->banco = $q->metodobs3;
            $q->id_banco = $q->id_metodobs3;
            $sum = 0;
            if ($q->montobs3) {$sum += $q->montobs3;}
            $q->monto_liquidado = $sum*-1;
            $q->monto = $sum*-1;
            if ($q->metodobs3) {
                return $q;
            }
        })->toArray(),function($q) {
            return $q!==null;
        });
        $bs4 = array_filter($cuenta4->map(function ($q) use ($bancoselect,$qbancobancosdata) {
            if ($qbancobancosdata) {
                if ($q->id_metodobs4==$bancoselect) {
                    
                }else{
                    return null;
                }
            }
            $q->banco = $q->metodobs4;
            $q->id_banco = $q->id_metodobs4;
            $sum = 0;
            if ($q->montobs4) {$sum += $q->montobs4;}
            $q->monto_liquidado = $sum*-1;
            $q->monto = $sum*-1;
            if ($q->metodobs4) {
                return $q;
            }
        })->toArray(),function($q) {
            return $q!==null;
        });
        $bs5 = array_filter($cuenta5->map(function ($q) use ($bancoselect,$qbancobancosdata) {
            if ($qbancobancosdata) {
                if ($q->id_metodobs5==$bancoselect) {
                    
                }else{
                    return null;
                }
            }
            $q->banco = $q->metodobs5;
            $q->id_banco = $q->id_metodobs5;
            $sum = 0;
            if ($q->montobs5) {$sum += $q->montobs5;}
            $q->monto_liquidado = $sum*-1;
            $q->monto = $sum*-1;
            if ($q->metodobs5) {
                return $q;
            }
        })->toArray(),function($q) {
            return $q!==null;
        });

        $bancosSum = [];
        $mergebs = [];

        $mergebs = array_merge($bs1,$bs2, array_merge($bs3,$bs4,$bs5));

        $puntosmascuentas = array_merge($puntosybiopagos->get()->toArray(), $mergebs);
        array_multisort(array_column($puntosmascuentas, $columnOrder), $order=="desc"? SORT_DESC: SORT_ASC, $puntosmascuentas);
        
        $xbanco = collect($puntosmascuentas)->merge($movsnoreportados)->map(function ($q) {
            if (str_contains($q["tipo"], "PUNTO")) {
                $q["tipo"] = "PUNTO";
            }

            if ($q["tipo"]=="BIOPAGO 1" OR $q["tipo"]=="BIOPAGO 2") {
                $q["tipo"] = "BIOPAGO";
            }

            $q["monto_comision"] =  $this->comision($q["monto"],$q["monto_liquidado"])["monto"];
            $q["porcentaje"] =  $this->comision($q["monto"],$q["monto_liquidado"])["porcentaje"];
            return $q;
        })
        ->groupBy("banco");

        foreach ($xbanco as $i => $e) {

            $monto_ingreso = $e->where("monto",">",0)->sum("monto");
            $monto_liquidado_ingreso = $e->where("monto_liquidado",">",0)->sum("monto_liquidado");

            $monto_egreso = $e->where("monto","<",0)->sum("monto");
            $monto_liquidado_egreso = $e->where("monto_liquidado","<",0)->sum("monto_liquidado");

            $bancosSum[$i] = [
                "ingreso" => [
                    "monto" => $monto_ingreso,
                    "monto_liquidado" => $monto_liquidado_ingreso, 
                    "monto_comision" => $this->comision($monto_ingreso,$monto_liquidado_ingreso)["monto"],
                    "porcentaje" => $this->comision($monto_ingreso,$monto_liquidado_ingreso)["porcentaje"],
                ],
                "egreso" => [
                    "monto" => $monto_egreso,
                    "monto_liquidado" => $monto_liquidado_egreso, 
                    "monto_comision" => $this->comision($monto_egreso,$monto_liquidado_egreso)["monto"],
                    "porcentaje" => $this->comision($monto_egreso,$monto_liquidado_egreso)["porcentaje"],
                ],
            ];

            foreach ($e->where("monto","<",0)->groupBy("tipo") as $tipoIndex => $tipos) {
                $monto = $tipos->sum("monto");
                $monto_liquidado = $tipos->sum("monto_liquidado");
                $bancosSum[$i]["egreso"][$tipoIndex] = [
                    "monto" => $monto,
                    "monto_liquidado" => $monto_liquidado,
                    "monto_comision" => $this->comision($monto,$monto_liquidado)["monto"],
                    "porcentaje" => $this->comision($monto,$monto_liquidado)["porcentaje"],
                    "movimientos" => $tipos,
                ];
            }

            foreach ($e->where("monto",">",0)->groupBy("tipo") as $tipoIndex => $tipos) {
                $monto = $tipos->sum("monto");
                $monto_liquidado = $tipos->sum("monto_liquidado");
                $bancosSum[$i]["ingreso"][$tipoIndex] = [
                    "monto" => $monto,
                    "monto_liquidado" => $monto_liquidado,
                    "monto_comision" => $this->comision($monto,$monto_liquidado)["monto"],
                    "porcentaje" => $this->comision($monto,$monto_liquidado)["porcentaje"],
                    "movimientos" => $tipos,
                ];
            }
        }
        $xfechaCuadreArr = collect($puntosmascuentas)->groupBy(["fecha_liquidacion","id_banco"]);
        $xfechaCuadre = [];
        $sum_cuadre = 0;
        foreach ($xfechaCuadreArr as $KeyfechasGroup => $fechasGroup) {
            foreach ($fechasGroup as $KeybancoGroup => $bancosGroup) {
                $ingresoBanco = 0;
                $egresoBanco = 0;
                foreach ($bancosGroup as $i => $e) {
                    if ($e["monto_liquidado"] < 0) {
                        $egresoBanco += $e["monto"];
                    }else{
                        if ($e["tipo"]==="Transferencia") {
                            $ingresoBanco += $e["monto_liquidado"];
                        }else{
                            $ingresoBanco += $e["monto"];

                        }
                    }
                }
                $noreportadaList = puntosybiopagos::where("categoria",66)->where("id_banco",$KeybancoGroup)->where("fecha_liquidacion",$KeyfechasGroup)->get();
                $noreportadasum = $noreportadaList->sum("monto_liquidado"); 

                $sireportadaList = puntosybiopagos::where("categoria",66)->where("id_banco",$KeybancoGroup)->where("fecha",$KeyfechasGroup)->get();
                $sireportadasum = $sireportadaList->sum("monto_liquidado"); 

                $q_banco = bancos::where("fecha",$KeyfechasGroup)->where("id_banco",$KeybancoGroup)->first();
                $inicial = $this->getSaldoInicialBanco($KeyfechasGroup,$KeybancoGroup);
                $inicial_fecha = bancos::where("id_banco",$KeybancoGroup)->where("fecha","<",$KeyfechasGroup)->orderBy("fecha","desc")->first();
                $fecha_inicial = $inicial_fecha? $inicial_fecha->fecha:"";

                $balance = $ingresoBanco+$egresoBanco+$inicial+abs($noreportadasum);

                $ban = bancos_list::find($KeybancoGroup);

                $banco_codigo = $ban? $ban->codigo: null;
                $color = $ban? $ban->color: null;
                $background = $ban? $ban->background: null;

                $cuadre = $q_banco? ($q_banco->saldo_real_manual+abs($sireportadasum)) - $balance: 0;
                array_push($xfechaCuadre, [
                    "fecha" => $KeyfechasGroup,
                    "banco" => $KeybancoGroup,
                    "id_banco" => $KeybancoGroup,
                    "banco_codigo" => $banco_codigo,
                    "color" => $color,
                    "background" => $background,
                    
                    
                    "ingreso" => $ingresoBanco,
                    "egreso" => $egresoBanco,
                    "inicial" => $inicial, 
                    "fecha_inicial" => $fecha_inicial, 
                    "balance" => $balance, 
                    "noreportadaList" => $noreportadaList, 
                    "noreportadasum" => $noreportadasum, 

                    "sireportadaList" => $sireportadaList, 
                    "sireportadasum" => $sireportadasum, 

                    

                    "guardado" => $q_banco, 
                    "saldoactual" => $q_banco? $q_banco->saldo: 0, 
                    
                    "cuadre" => $cuadre, 


                ]);
                $sum_cuadre += $cuadre;

            }
        }
        $all_bancos = bancos_list::when($qbancobancosdata!="",function($q) use ($qbancobancosdata) {
            $q->where("id",$qbancobancosdata);
        })
        ->get();


        $begin = new \DateTime($qfechabancosdata);
        $end = new \DateTime($fechaHastaSelectAuditoria);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);


        foreach ($all_bancos as $i => $ban) {
            
            foreach ($period as $dt) {
                $fecha = $dt->format("Y-m-d");
                $id_banco = $ban->id;

                $fil = array_filter($xfechaCuadre,function($q) use ($fecha,$id_banco) {
                    return $q["fecha"]==$fecha && $q["id_banco"]==$id_banco;
                });

                if (!count($fil)) {
                    $ingresoBanco = 0;
                    $egresoBanco = 0;

                    $noreportadaList = puntosybiopagos::where("categoria",66)->where("id_banco",$id_banco)->where("fecha_liquidacion",$fecha)->get();
                    $noreportadasum = $noreportadaList->sum("monto_liquidado"); 

                    $sireportadaList = puntosybiopagos::where("categoria",66)->where("id_banco",$id_banco)->where("fecha",$fecha)->get();
                    $sireportadasum = $sireportadaList->sum("monto_liquidado"); 

                    $q_banco = bancos::where("fecha",$fecha)->where("id_banco",$id_banco)->first();
                    $inicial = $this->getSaldoInicialBanco($fecha,$id_banco);
                    
                    $inicial_fecha = bancos::where("id_banco",$id_banco)->where("fecha","<",$fecha)->orderBy("fecha","desc")->first();
                    $fecha_inicial = $inicial_fecha? $inicial_fecha->fecha:"";
                    
                    $balance = $ingresoBanco+$egresoBanco+$inicial+abs($noreportadasum);
                    $cuadre = $q_banco? ($q_banco->saldo_real_manual+abs($sireportadasum)) - $balance: 0;
                    array_push($xfechaCuadre, [
                        "fecha" => $fecha,
                        "banco" => $id_banco,
                        "id_banco" => $id_banco,
                        "banco_codigo" => $ban->codigo,
                        "color" => $ban->color,
                        "background" => $ban->background,
                        "fecha_inicial" => $fecha_inicial, 
                        
                        "ingreso" => $ingresoBanco,
                        "egreso" => $egresoBanco,
                        "inicial" => $inicial, 
                        "balance" => $balance, 
                        "noreportadaList" => $noreportadaList, 
                        "noreportadasum" => $noreportadasum, 

                        "sireportadaList" => $sireportadaList, 
                        "sireportadasum" => $sireportadasum, 

                        

                        "guardado" => $q_banco, 
                        "saldoactual" => $q_banco? $q_banco->saldo: 0, 
                        
                        "cuadre" => $cuadre, 


                    ]);


                }

            }
        }



        array_multisort(array_column($xfechaCuadre, "fecha"), SORT_ASC, $xfechaCuadre);
        array_multisort(array_column($xfechaCuadre, "banco_codigo"), SORT_ASC, $xfechaCuadre);

       
        $porliquidar = $puntosybiopagos->get()->merge($movsnoreportados)->merge($movsyareportados)->toArray();
        array_multisort(array_column($porliquidar, $columnOrder), $order=="desc"? SORT_DESC: SORT_ASC, $porliquidar);

        return [
            "xfechaCuadreArr"=>$xfechaCuadreArr,
            "xfechaCuadre" => $xfechaCuadre,
            "puntosybiopagosxbancos" => $bancosSum,
            "sum" => $sum_cuadre,
            "movsnoreportados" => $movsnoreportados,
            "movsnoreportadossum" => $movsnoreportadossum,

            "movsnoreportadosTotal" => $movsnoreportadosTotal, 
            "movsnoreportadosTotalsum" => $movsnoreportadosTotalsum, 
            "xliquidar" => $porliquidar, 
            "estado" => true,
            "view" => $subviewAuditoria,
        ];
    }
    function getBancosData(Request $req) {
        try {
            $qdescripcionbancosdata = $req->qdescripcionbancosdata;
            $qbancobancosdata = $req->bancoSelectAuditoria;
            $qfechabancosdata = $req->fechaSelectAuditoria;
            $fechaHastaSelectAuditoria = $req->fechaHastaSelectAuditoria;
            $sucursalSelectAuditoria = $req->sucursalSelectAuditoria;
            $subviewAuditoria = $req->subviewAuditoria;
            $columnOrder = $req->orderColumnAuditoria;
            $order = $req->orderAuditoria;
            $tipoSelectAuditoria = $req->tipoSelectAuditoria;
            $showallSelectAuditoria = $req->showallSelectAuditoria;
            $ingegreSelectAuditoria = $req->ingegreSelectAuditoria;
            
            

            return $this->bancosDataFun([
                "qdescripcionbancosdata" => $qdescripcionbancosdata,
                "qbancobancosdata" => $qbancobancosdata,
                "qfechabancosdata" => $qfechabancosdata,
                "fechaHastaSelectAuditoria" => $fechaHastaSelectAuditoria,
                "sucursalSelectAuditoria" => $sucursalSelectAuditoria,
                "subviewAuditoria" => $subviewAuditoria,
                "columnOrder" => $columnOrder,
                "order" => $order,
                "tipoSelectAuditoria" => $tipoSelectAuditoria,
                "showallSelectAuditoria" => $showallSelectAuditoria,
                "ingegreSelectAuditoria" => $ingegreSelectAuditoria,
                
                
            ]);
            
    
            
        } catch (\Exception $err) {
            return $err->getMessage()." LINEA ".$err->getLine();
        }
    }
    function comision($monto,$liquidado) {
        $monto = $monto?$monto:0;
        $liquidado = $liquidado?$liquidado:0;
        $m = $monto - $liquidado;
        $p = 0;
        if ($m!=0 && $monto!= 0) {
            $p = $m/$monto*100;
        }


        return [
            "monto" => $m,
            "porcentaje" => $p,
        ];
    }
    function getSaldoInicialBanco($fecha, $banco) {
        $saldo = bancos::where("id_banco",$banco)->where("fecha","<",$fecha)->orderBy("fecha","desc")->first("saldo");
        if (! $saldo) {
            return 0;
        }
        return $saldo->saldo;
    }
    function sendsaldoactualbancofecha(Request $req) {  
        $banco = $req->banco;
        $fecha = $req->fecha;
        $saldo = $req->saldo;

        $debetenersegunsistema = $req->debetenersegunsistema;
        $saldo_inicial = $req->saldo_inicial;
        $ingreso = $req->ingreso;
        $egreso = $req->egreso;
        $bancocuadres_sireportadasum = $req->bancocuadres_sireportadasum;
        
        
        $banco_codigo = bancos_list::find($banco)->codigo;

        $ban = bancos::updateOrCreate(["id_banco"=>$banco, "fecha" => $fecha],[
            "id_usuario" => null,
            "descripcion" => null,
            "saldo" => $debetenersegunsistema-$bancocuadres_sireportadasum,
            "banco" => $banco_codigo,

            "saldo_real_manual" =>$saldo,
            "saldo_inicial" =>$saldo_inicial,
            "ingreso" =>$ingreso,
            "egreso" =>$egreso,
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


    function copyBancos() {
        $list = bancos_list::all();

        foreach ($list as $i => $banco) {
            puntosybiopagos::where("banco",$banco->codigo)->update([
                "id_banco"=>$banco->id,
            ]);

            bancos::where("banco",$banco->codigo)->update([
                "id_banco"=>$banco->id,
            ]);
            
            cuentasporpagar::where("metodobs1",$banco->codigo)->update([
                "id_metodobs1"=>$banco->id,
            ]);
            
            
            cuentasporpagar::where("metodobs2",$banco->codigo)->update([
                "id_metodobs2"=>$banco->id,
            ]);
            
            
            cuentasporpagar::where("metodobs3",$banco->codigo)->update([
                "id_metodobs3"=>$banco->id,
            ]);
            
            
            cuentasporpagar::where("metodobs4",$banco->codigo)->update([
                "id_metodobs4"=>$banco->id,
            ]);
            

            cuentasporpagar::where("metodobs5",$banco->codigo)->update([
                "id_metodobs5"=>$banco->id,
            ]);

        }
    }
}
