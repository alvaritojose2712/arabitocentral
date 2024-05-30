<?php

namespace App\Http\Controllers;

use App\Models\cierres;
use App\Models\cajas;
use App\Models\comovamos;
use App\Models\creditos;
use App\Models\inventario_sucursal;
use App\Models\nomina;
use App\Models\puntosybiopagos;
use App\Models\sucursal;
use App\Models\ultimainformacioncargada;
use App\Models\garantias;
use App\Models\fallas;


use DateTime;
use Illuminate\Http\Request;


class CierresController extends Controller
{

    function setAll(Request $req) {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];
        $today = (new NominaController)->today();
        
        garantias::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        fallas::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        cajas::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        puntosybiopagos::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->whereNull("fecha_liquidacion")->delete();
        cierres::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();

        $sendInventarioCt = (new InventarioSucursalController)->sendInventarioCt($req->sendInventarioCt, $id_sucursal);
        $sendGarantias = (new GarantiasController)->sendGarantias($req->sendGarantias, $id_sucursal);
        $sendFallas = (new FallasController)->sendFallas($req->sendFallas, $id_sucursal);
        $setCierreFromSucursalToCentral = (new CierresController)->setCierreFromSucursalToCentral($req->setCierreFromSucursalToCentral, $id_sucursal);
        $setEfecFromSucursalToCentral = (new CajasController)->setEfecFromSucursalToCentral($req->setEfecFromSucursalToCentral, $id_sucursal);

        $sendCreditos = (new CreditosController)->sendCreditos($req->sendCreditos, $id_sucursal);

        if (!isset($setEfecFromSucursalToCentral["last"])) {return "setEfecFromSucursalToCentral: ".$setEfecFromSucursalToCentral;}
        if (!isset($setCierreFromSucursalToCentral["last"])) {return "setCierreFromSucursalToCentral: ".$setCierreFromSucursalToCentral;}
        if (!isset($sendGarantias["last"])) {return "sendGarantias: ".$sendGarantias;}
        if (!isset($sendFallas["last"])) {return "sendFallas: ".$sendFallas;}
        if (!isset($sendCreditos["last"])) {return "sendCreditos: ".$sendCreditos;}

        ultimainformacioncargada::updateOrCreate([
            "id_sucursal" =>$id_sucursal,
            "fecha" => $today
        ],[
            "id_sucursal" => $id_sucursal,
            "fecha" => $today,

            "date_last_cierres" => $setCierreFromSucursalToCentral["last"],
            "id_last_efec" => $setEfecFromSucursalToCentral["last"],
            "id_last_garantias" => $sendGarantias["last"],
            "id_last_fallas" => $sendFallas["last"],
        ]);
        return [
            $sendInventarioCt,
            $sendGarantias["msj"],
            $sendFallas["msj"],
            $setCierreFromSucursalToCentral["msj"],
            $setEfecFromSucursalToCentral["msj"],
            $sendCreditos["msj"],
        ];
       
    }
    public function setCierreFromSucursalToCentral($cierres,$id_origen)
    {
        try {
            $num = 0;
            $last = new DateTime("2000-01-01");
            $numlote = 0;
            $totlote = 0;
            
            foreach ($cierres as $data) {
                $cierre = $data["cierre"];
                $lotes = $data["lotes"];

                
                $fecha = new DateTime($cierre["fecha"]);
                if ($last < $fecha) {
                    $last = $fecha;
                }
                $totlote += count($lotes);
                foreach ($lotes as $lote) {

                    $ispermiso = true;
                    $checkliqui = puntosybiopagos::where("fecha",$lote["fecha"])
                    ->where("id_usuario",$lote["id_usuario"])
                    ->where("id_sucursal",$id_origen)
                    ->where("tipo",$lote["tipo"])->first();
                    if ($checkliqui) {
                        if ($checkliqui->fecha_liquidacion) {
                            $ispermiso = false;
                        }
                    }

                    if ($ispermiso) {
                        $loteSql = puntosybiopagos::updateOrCreate([
                            "fecha" => $lote["fecha"],
                            "id_usuario" => $lote["id_usuario"],
                            "id_sucursal" => $id_origen,
                            "tipo" => $lote["tipo"],
                        ], [
                            "loteserial" => $lote["lote"],
                            "monto" => $lote["monto"],
                            "banco" => $lote["banco"],
                            "debito_credito" => isset($lote["categoria"])?$lote["categoria"]:null,
                            "fecha_liquidacion" => /* $lote["tipo"]=="Transferencia"? $lote["fecha"]: */ null,
                            "monto_liquidado" => /* $lote["tipo"]=="Transferencia"? $lote["monto"]: */ null,
                        ]);
    
                        if ($loteSql) {
                            $numlote++;
                        }
                    }
                }
                $cierresobj = cierres::updateOrCreate([
                    "fecha" => $cierre["fecha"],
                    "id_sucursal" => $id_origen,
                ], [
                    "debito" => $cierre["debito"],
                    "efectivo" => $cierre["efectivo"],
                    "transferencia" => $cierre["transferencia"],
                    "caja_biopago" => $cierre["caja_biopago"],
                    "dejar_dolar" => $cierre["dejar_dolar"],
                    "dejar_peso" => $cierre["dejar_peso"],
                    "dejar_bss" => $cierre["dejar_bss"],
                    "efectivo_guardado" => $cierre["efectivo_guardado"],
                    "efectivo_guardado_cop" => $cierre["efectivo_guardado_cop"],
                    "efectivo_guardado_bs" => $cierre["efectivo_guardado_bs"],
                    "tasa" => $cierre["tasa"],
                    "nota" => $cierre["nota"],
    
    
                    "numventas" => $cierre["numventas"],
                    "precio" => $cierre["precio"],
                    "precio_base" => $cierre["precio_base"],
                    "ganancia" => $cierre["ganancia"],
                    "porcentaje" => $cierre["porcentaje"],
                    "desc_total" => $cierre["desc_total"],
                    "efectivo_actual" => $cierre["efectivo_actual"],
                    "efectivo_actual_cop" => $cierre["efectivo_actual_cop"],
                    "efectivo_actual_bs" => $cierre["efectivo_actual_bs"],
                    "puntodeventa_actual_bs" => $cierre["puntodeventa_actual_bs"],
                    "tasacop" => $cierre["tasacop"],
                    "inventariobase" => $cierre["inventariobase"],
                    "inventarioventa" => $cierre["inventarioventa"],
                    "numreportez" => $cierre["numreportez"],
                    "ventaexcento" => $cierre["ventaexcento"],
                    "ventagravadas" => $cierre["ventagravadas"],
                    "ivaventa" => $cierre["ivaventa"],
                    "totalventa" => $cierre["totalventa"],
                    "ultimafactura" => $cierre["ultimafactura"],
                    "credito" => $cierre["credito"],
                    "creditoporcobrartotal" => $cierre["creditoporcobrartotal"],
                    "vueltostotales" => $cierre["vueltostotales"],
                    "abonosdeldia" => $cierre["abonosdeldia"],
                    "efecadiccajafbs" => $cierre["efecadiccajafbs"],
                    "efecadiccajafcop" => $cierre["efecadiccajafcop"],
                    "efecadiccajafdolar" => $cierre["efecadiccajafdolar"],
                    "efecadiccajafeuro" => $cierre["efecadiccajafeuro"],
    
                    "puntolote1" => $cierre["puntolote1"],
                    "puntolote1montobs" => $cierre["puntolote1montobs"],
                    "puntolote2" => $cierre["puntolote2"],
                    "puntolote2montobs" => $cierre["puntolote2montobs"],
                    "biopagoserial" => $cierre["biopagoserial"],
                    "biopagoserialmontobs" => $cierre["biopagoserialmontobs"],
    
    
                ]);
                
                   
               

                if ($cierresobj->save()) {
                    $num++;
                }
            }
            return [
                "msj" => "OK CIERRES ".$num." / ".count($cierres)." - LOTES y SERIALES $numlote / $totlote",
                "last" => $last->format('Y-m-d')
            ];
        } catch (\Exception $e) {
            return "Error TRY CENTRAL: " . $e->getMessage()." ".$e->getLine();
        }
    }
    public function getCierres($fechasMain1, $fechasMain2, $filtros)
    {
        return sucursal::all()->map(function ($q) use ($fechasMain1, $fechasMain2) {
            $cierre = cierres::where("id_sucursal", $q->id)
                ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
                ->orderBy("fecha", "desc")
                ->get();

            $d = $cierre->sum("debito");
            $e = $cierre->sum("efectivo");
            $t = $cierre->sum("transferencia");

            $q->numventastotal = $cierre->sum("numventas");
            $q->debitototal = moneda($d);
            $q->efectivototal = moneda($e);
            $q->transferenciatotal = moneda($t);
            $q->total = moneda($d + $e + $t);
            $q->gananciatotal = moneda($cierre->sum("ganancia"));
            $q->porcentajetotal = $cierre->avg("porcentaje");

            return $q;
        });

        //"*,sum(numventas) as  numventastotal, sum(debito) as debitototal, sum(efectivo) as efectivototal, sum(transferencia) as transferenciatotal,(transferencia) as total, sum(ganancia) as  gananciatotal, avg(porcentaje) as  porcentajetotal
    }
    public function getsucursalListData(Request $req)
    {
        $fechasMain1 = $req->fechasMain1;
        $fechasMain2 = $req->fechasMain2;
        $filtros = $req->filtros;

        $viewmainPanel = $req->viewmainPanel;

        switch ($viewmainPanel) {
            case 'panel':

                break;
            case 'cierres':
                return $this->getCierres($fechasMain1, $fechasMain2, $filtros);
                break;
            case 'inventario':

                break;
            case 'gastos':
                return (new GastosController)->getGastos($fechasMain1, $fechasMain2, $filtros);

                break;
        }
    }
    public function getCierreSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        //debug_to_console($id_sucursal);
        $array = cierres::with("sucursal")
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("id_sucursal", $id_sucursal);
            })
            ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
            ->orderBy("fecha","desc")
            ->get();

        $sumdebito = $array->sum("debito");
        $sumefectivo = $array->sum("efectivo");
        $sumtransferencia = $array->sum("transferencia");
        $sumbiopago = $array->sum("caja_biopago");

        $sum = [
            "numventas" => $array->sum("numventas"),
            "numero" => $array->count(),
            "total" => moneda($sumdebito + $sumefectivo + $sumtransferencia + $sumbiopago),
            "debito" => moneda($sumdebito),
            "efectivo" => moneda($sumefectivo),
            "transferencia" => moneda($sumtransferencia),
            "biopago" => moneda($sumbiopago),

            "debito_clean" => ($sumdebito),
            "efectivo_clean" => ($sumefectivo),
            "transferencia_clean" => ($sumtransferencia),
            "biopago_clean" => ($sumbiopago),
            "total_clean" => ($sumdebito + $sumefectivo + $sumtransferencia + $sumbiopago),
            "ganancia_clean" => ($array->sum("ganancia")),




            "efectivo_guardado" => moneda($array->sum("efectivo_guardado")),
            "efectivo_guardado_cop" => moneda($array->sum("efectivo_guardado_cop")),
            "efectivo_guardado_bs" => moneda($array->sum("efectivo_guardado_bs")),
            "efectivo_actual" => moneda($array->sum("efectivo_actual")),
            "efectivo_actual_cop" => moneda($array->sum("efectivo_actual_cop")),
            "efectivo_actual_bs" => moneda($array->sum("efectivo_actual_bs")),
            "caja_biopago" => moneda($array->sum("caja_biopago")),
            "puntodeventa_actual_bs" => moneda($array->sum("puntodeventa_actual_bs")),
            "tasa" => moneda($array->avg("tasa")),
            "precio" => moneda($array->sum("precio")),
            "precio_base" => moneda($array->sum("precio_base")),
            "ganancia" => moneda($array->sum("ganancia")),
            "porcentaje" => moneda($array->sum("porcentaje")),
            "desc_total" => moneda($array->sum("desc_total")),
            "tasacop" => moneda($array->sum("tasacop")),
            "ventaexcento" => moneda($array->sum("ventaexcento")),
            "ventagravadas" => moneda($array->sum("ventagravadas")),
            "ivaventa" => moneda($array->sum("ivaventa")),
            "totalventa" => moneda($array->sum("totalventa")),
            "credito" => moneda($array->sum("credito")),
            "abonosdeldia" => moneda($array->sum("abonosdeldia")),
            "efecadiccajafbs" => moneda($array->sum("efecadiccajafbs")),
            "efecadiccajafcop" => moneda($array->sum("efecadiccajafcop")),
            "efecadiccajafdolar" => moneda($array->sum("efecadiccajafdolar")),
            "efecadiccajafeuro" => moneda($array->sum("efecadiccajafeuro")),
            
            "inventariobase" => moneda($array->sum("inventariobase")),
            "inventarioventa" => moneda($array->sum("inventarioventa")),

            "inventariobase_clean" => $array->sum("inventariobase"),
            "inventarioventa_clean" => $array->sum("inventarioventa"),

            

        ];


        $array = $array->map(function ($q) {
            $q->total = moneda($q->debito + $q->efectivo + $q->transferencia + $q->caja_biopago);

            $q->debito = moneda($q->debito);
            $q->efectivo = moneda($q->efectivo);
            $q->transferencia = moneda($q->transferencia);
            $q->dejar_dolar = moneda($q->dejar_dolar);
            $q->dejar_peso = moneda($q->dejar_peso);
            $q->dejar_bss = moneda($q->dejar_bss);
            $q->efectivo_guardado = moneda($q->efectivo_guardado);
            $q->efectivo_guardado_cop = moneda($q->efectivo_guardado_cop);
            $q->efectivo_guardado_bs = moneda($q->efectivo_guardado_bs);
            $q->efectivo_actual = moneda($q->efectivo_actual);
            $q->efectivo_actual_cop = moneda($q->efectivo_actual_cop);
            $q->efectivo_actual_bs = moneda($q->efectivo_actual_bs);
            $q->caja_biopago = moneda($q->caja_biopago);
            $q->puntodeventa_actual_bs = moneda($q->puntodeventa_actual_bs);
            $q->tasa = moneda($q->tasa);
            $q->precio = moneda($q->precio);
            $q->precio_base = moneda($q->precio_base);
            $q->ganancia = moneda($q->ganancia);
            $q->porcentaje = moneda($q->porcentaje);
            $q->desc_total = moneda($q->desc_total);
            $q->tasacop = moneda($q->tasacop);
            $q->inventariobase = moneda($q->inventariobase);
            $q->inventarioventa = moneda($q->inventarioventa);
            $q->ventaexcento = moneda($q->ventaexcento);
            $q->ventagravadas = moneda($q->ventagravadas);
            $q->ivaventa = moneda($q->ivaventa);
            $q->totalventa = moneda($q->totalventa);
            $q->credito = moneda($q->credito);
            $q->creditoporcobrartotal = moneda($q->creditoporcobrartotal);
            $q->vueltostotales = moneda($q->vueltostotales);
            $q->abonosdeldia = moneda($q->abonosdeldia);
            $q->efecadiccajafbs = moneda($q->efecadiccajafbs);
            $q->efecadiccajafcop = moneda($q->efecadiccajafcop);
            $q->efecadiccajafdolar = moneda($q->efecadiccajafdolar);
            $q->efecadiccajafeuro = moneda($q->efecadiccajafeuro);


            return $q;
        })
        ;



        return [
            "data" => $array,
            "sum" => $sum,
        ];

    }
    public function getCierreSucursalResumen($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        //debug_to_console($id_sucursal);
        $array = cierres::with("sucursal")
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->orderBy("fecha","asc")
        ->get();


        $sumdebito = $array->sum("debito");
        $sumefectivo = $array->sum("efectivo");
        $sumtransferencia = $array->sum("transferencia");
        $sumbiopago = $array->sum("caja_biopago");

        $sum = [
            "numventas" => $array->sum("numventas"),
            "numero" => $array->count(),
            "total" => moneda($sumdebito + $sumefectivo + $sumtransferencia + $sumbiopago),
            "precio" => moneda($array->sum("precio")),
            "precio_base" => moneda($array->sum("precio_base")),
            "ganancia" => moneda($array->sum("ganancia")),
            "porcentaje" => moneda($array->sum("porcentaje")),
            "desc_total" => moneda($array->sum("desc_total")),

        ];

        $array = $array->map(function ($q) {
            $q->total = ($q->debito + $q->efectivo + $q->transferencia + $q->caja_biopago);
            $q->precio = ($q->precio);
            $q->precio_base = ($q->precio_base);
            $q->ganancia = ($q->ganancia);
            $q->porcentaje = ($q->porcentaje);
            $q->desc_total = ($q->desc_total);
            
            
            $orderdate = explode('-', $q->fecha);
            $year  = $orderdate[0];
            $month = $orderdate[1];
            $day   = $orderdate[2];
            
            $q->dia = $day;
            $q->mes = $month;
            $q->ano = $year;
            return $q;
        });

        $dataGroup = [];
        foreach ($array as $i => $cierre) {
            $id_sucursal = $cierre["sucursal"]["codigo"]; 

            if (!isset($dataGroup[$id_sucursal])) {
                $dataGroup[$id_sucursal] = [
                    "cierres" => [$cierre],
                    "total" => floatval($cierre["total"]),
                    "precio" => floatval($cierre["precio"]),
                    "precio_base" => floatval($cierre["precio_base"]),
                    "ganancia" => floatval($cierre["ganancia"]),
                    "porcentaje" => floatval($cierre["porcentaje"]),
                    "desc_total" => floatval($cierre["desc_total"]),
                    "numventas" => floatval($cierre["numventas"]),
                ];
            }else{
                
                $dataGroup[$id_sucursal] = [
                    "cierres" => array_merge($dataGroup[$id_sucursal]["cierres"],[$cierre]),
                    "total" => floatval($cierre["total"]) + $dataGroup[$id_sucursal]["total"],
                    "precio" => floatval($cierre["precio"]) + $dataGroup[$id_sucursal]["precio"],
                    "precio_base" => floatval($cierre["precio_base"]) + $dataGroup[$id_sucursal]["precio_base"],
                    "ganancia" => floatval($cierre["ganancia"]) + $dataGroup[$id_sucursal]["ganancia"],
                    "porcentaje" => floatval($cierre["porcentaje"]) + $dataGroup[$id_sucursal]["porcentaje"],
                    "desc_total" => floatval($cierre["desc_total"]) + $dataGroup[$id_sucursal]["desc_total"],
                    "numventas" => floatval($cierre["numventas"]) + $dataGroup[$id_sucursal]["numventas"],
                ];
            }
        }

        return [
            "data" => $dataGroup,
            "sum" => $sum,
        ];

    }
    
    public function getsucursalDetallesData(Request $req)
    {
        $id_sucursal = $req->sucursalSelect;
        $fechasMain1 = $req->fechasMain1;
        $fechasMain2 = $req->fechasMain2;
        $filtros = $req->filtros;
        $subviewpanelsucursales = $req->subviewpanelsucursales;
        return $this->getsucursalDetallesDataFun([
            "id_sucursal" => $id_sucursal,
            "fechasMain1" => $fechasMain1,
            "fechasMain2" => $fechasMain2,
            "filtros" => $filtros,
            "subviewpanelsucursales" => $subviewpanelsucursales,
        ]);
    }
    
    function getsucursalDetallesDataFun($arr) {
        $tipo_usuario = session("tipo_usuario");
        $id_sucursal = $arr["id_sucursal"];
        $fechasMain1 = $arr["fechasMain1"];
        $fechasMain2 = $arr["fechasMain2"];
        $filtros = $arr["filtros"];
        $subviewpanelsucursales = $arr["subviewpanelsucursales"];
    
        switch ($subviewpanelsucursales) {
            case 'inventario':
                return $this->getInvSucursal($id_sucursal, $filtros);
                break;
            case 'puntosyseriales':
                return $this->getPuntosyseriales($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                break;
            case 'panel':
                break;
            case 'cierres':
                if (true) {
                    
                    return $this->getCierreSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'resumencierres':
                if (true) {
                    
                    return $this->getCierreSucursalResumen($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                break;
            case 'controldeefectivo':
                if (true || $tipo_usuario==4) {
                    
                    return $this->getControldeefectivo($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'nomina':
                if (true) {
                    
                    return $this->getNominasSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'comovamos':
                if (true) {
                    
                    return $this->comovamos($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
            }case
             'fallas':
                if (true) {
                    
                    return $this->getFallas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'creditos':
                if (true) {
                    
                    return $this->getCreditos($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
    
            case 'aprobacioncajafuerte':
            if (true) {
                
                return (new CajasAprobacionController)->getAprobacionCajas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
            }
                break;
    
            case 'porcobrar':
                if (true) {
                    
                    return (new CreditoAprobacionController)->getCreditoAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;
    
            case 'cuentasporpagar':
                if (true) {
                    
                    return (new CuentasporpagarController)->getCuentas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;
    
            case 'cuentasporpagardetalles':
                if (true) {
                    
                    return (new CuentasporpagarController)->selectCuentaPorPagarProveedorDetalles($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;
    
            case 'aprobtransferencia':
                if (true) {
                    
                    return (new TransferenciaAprobacionController)->gettransferenciaAprobacion($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;
    
                    
    
    
        }
    }



    function getCreditos($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $data = creditos::with(["sucursal","cliente"]) 
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })->orderBy("saldo","asc");

        return [
            "data" => $data->get(),
            "num" => $data->sum("saldo"),
        ];
    }
    function getFallas($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $data = fallas::with("sucursal") 
        ->where("id_sucursal", $id_sucursal)
        ->orderBy("cantidad","asc")
        ->get()
        ->map(function($q) use ($id_sucursal) {
            $q->producto = inventario_sucursal::where("idinsucursal",$q->id_producto)->where("id_sucursal", $id_sucursal)->first();
            return $q;
        });

        $sum = [];

        return [
            "data" => $data,
            "sum" => $sum, 
        ];
    }
    function comovamos($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        $c = comovamos::with("sucursal")
            ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
            ->orderBy("total", "desc")
            ->get()
            ->map(function($q){
                $q->ticked = 0;
                if ($q->numventas && $q->total) {
                    $q->ticked = number_format($q->total/$q->numventas,2);
                }
                return $q;
            });

        return [
            "comovamos" => $c,
            "sum" =>[
                "transferencia" => $c->sum("transferencia"),
                "biopago" => $c->sum("biopago"),
                "debito" => $c->sum("debito"),
                "efectivo" => $c->sum("efectivo"),
                "numventas" => $c->sum("numventas"),
                "total" => $c->sum("total"),
                "ticked" => number_format($c->avg("ticked"),2),
            ]
            ];
    }
    function getInvSucursal($id_sucursal, $filtros)
    {

        $itemCero = $filtros["itemCero"];
        $q = $filtros["q"];
        $exacto = $filtros["exacto"];
        $num = $filtros["num"];
        $orderColumn = $filtros["orderColumn"];
        $orderBy = $filtros["orderBy"];

        return inventario_sucursal::with("sucursal")
            ->where(function ($e) use ($itemCero, $q, $exacto) {
                $e->orWhere("descripcion", "LIKE", "%$q%")
                    ->orWhere("codigo_proveedor", "LIKE", "%$q%")
                    ->orWhere("codigo_barras", "LIKE", "%$q%");
            })
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("id_sucursal", $id_sucursal);
            })
            ->limit($num)
            ->orderBy("id_sucursal", "desc")
            ->orderBy("cantidad", "desc")
            ->get();
    }
    function getBalanceGeneral(Request $req) {
        $sucursalBalanceGeneral = $req->sucursalBalanceGeneral;
        $fechaBalanceGeneral = $req->fechaBalanceGeneral;
        $fechaHastaBalanceGeneral = $req->fechaHastaBalanceGeneral;

        if (!$fechaBalanceGeneral || !$fechaHastaBalanceGeneral) {
            return ["Seleccione ambas Fechas"];
        }
        $bsq = cierres::orderBy("id","desc")->first(["tasa","tasacop"]);
        $bs = 1;
        $cop = 1;
        if ($bsq) {
            $bs = $bsq->tasa;
            $cop = $bsq->tasacop;
        }

        $sumArrcat = [];
        $sumArrcatgeneral = [];
        $sumArringresoegreso = [];
        $gastosFun = (new PuntosybiopagosController)
        ->getGastosFun([
            "gastosQ"=>"",
            "gastosQFecha"=>$fechaBalanceGeneral,
            "gastosQFechaHasta"=>$fechaHastaBalanceGeneral,
            "gastosQCategoria"=>"",
            "catgeneral"=>"",
            "ingreso_egreso"=>"",
            "typecaja"=>"",
            "gastosorder"=>"desc",
            "gastosfieldorder"=>"id",
        ])["data"];
        foreach ($gastosFun as $gastoi => $gasto) {
            $ingresoegreso_key = $gasto["ingreso_egreso"];
            $cat_key = $gasto["categoria"];
            $catgeneral_key = $gasto["catgeneral"];

            $monto =  $gasto["montodolar"]+($gasto["montobs"]/$bs)+($gasto["montopeso"]/$cop);
            if (array_key_exists($catgeneral_key, $sumArrcatgeneral)) {
                $sumArrcatgeneral[$catgeneral_key]["sumdolar"] = $sumArrcatgeneral[$catgeneral_key]["sumdolar"] + $monto;  
            }else{
                $sumArrcatgeneral[$catgeneral_key] = [
                    "sumdolar" => $monto,
                ];
            }

            if (array_key_exists($cat_key, $sumArrcat)) {
                $sumArrcat[$cat_key]["sumdolar"] = $sumArrcat[$cat_key]["sumdolar"] + $monto;  
            }else{
                $sumArrcat[$cat_key] = [
                    "sumdolar" => $monto,
                ];
            }

            if (array_key_exists($ingresoegreso_key, $sumArringresoegreso)) {
                $sumArringresoegreso[$ingresoegreso_key]["sumdolar"] = $sumArringresoegreso[$ingresoegreso_key]["sumdolar"] + $monto;  
            }else{
                $sumArringresoegreso[$ingresoegreso_key] = [
                    "sumdolar" => $monto,
                ];
            }
            $gastosFun[$gastoi]["montofull"] = $monto;
        }
        $gastos = collect($gastosFun)->groupBy(["ingreso_egreso","catgeneral","categoria"]);



        $arr = [];
        $dolarbalance = 0;
        $su = sucursal::all();
        foreach ($su as $sucursal) {
            $c = cajas::with("sucursal")->where("id_sucursal",$sucursal->id)->where("concepto","LIKE","%INGRESO DESDE CIERRE%")
            ->whereBetween("fecha", [$fechaBalanceGeneral, !$fechaHastaBalanceGeneral?$fechaBalanceGeneral:$fechaHastaBalanceGeneral])
            ->orderBy("fecha","desc")
            ->first();
            if ($c) {
                array_push($arr, $c);
                $dolarbalance += $c->dolarbalance+($c->bsbalance/$bs)+($c->pesobalance/$cop)+($c->eurobalance);
            }
        }
        $efectivoData  = [
            "data" => $arr,
            "dolarbalance" => $dolarbalance,
        ];

        
        
        $bancoData = (new BancosController)->bancosDataFun([
            "qdescripcionbancosdata" => "",
            "qbancobancosdata" => "",
            "qfechabancosdata" => $fechaBalanceGeneral,
            "fechaHastaSelectAuditoria" => $fechaHastaBalanceGeneral,
            "sucursalSelectAuditoria" => $sucursalBalanceGeneral,
            "subviewAuditoria" => "conciliacion",
            "columnOrder" => "tipo",
            "order" => "desc",
        ]);
        $banco = array_sum(array_column($bancoData["xfechaCuadre"],"saldoactual"))/$bs;

        $cierreData = $this->getsucursalDetallesDataFun([
            "id_sucursal" => $sucursalBalanceGeneral,
            "fechasMain1" => $fechaBalanceGeneral,
            "fechasMain2" => $fechaHastaBalanceGeneral,
            "filtros" => [
                "controlefecQDescripcion"=>"",
                "controlefecSelectCat"=>"",
                "controlefecSelectGeneral"=>1,
                "exacto"=>"",
                "filtronominacargo"=>"",
                "filtronominaq"=>"",
                "itemCero"=>"",
                "num"=>"25",
                "orderBy"=>"desc",
                "orderColumn"=>"descripcion",
                "q"=>"",
                "qcuentasPorPagar"=>"",
                "qestatusaprobaciocaja"=>0,
            ],
            "subviewpanelsucursales" => "cierres",
        ]);
        
        $inventario = $cierreData["sum"]["inventariobase_clean"];
        $efectivo = $cierreData["sum"]["efectivo_clean"];
        $debito = ($cierreData["sum"]["debito_clean"]);
        $transferencia = $cierreData["sum"]["transferencia_clean"];
        $biopago = $cierreData["sum"]["biopago_clean"];
        $total = $cierreData["sum"]["total_clean"];
        $ganancia = $cierreData["sum"]["ganancia_clean"];
        
        
        $cxcData = $this->getsucursalDetallesDataFun([
            "id_sucursal" => $sucursalBalanceGeneral,
            "fechasMain1" => $fechaBalanceGeneral,
            "fechasMain2" => $fechaHastaBalanceGeneral,
            "filtros" => [
                "controlefecQDescripcion"=>"",
                "controlefecSelectCat"=>"",
                "controlefecSelectGeneral"=>1,
                "exacto"=>"",
                "filtronominacargo"=>"",
                "filtronominaq"=>"",
                "itemCero"=>"",
                "num"=>"25",
                "orderBy"=>"desc",
                "orderColumn"=>"descripcion",
                "q"=>"",
                "qcuentasPorPagar"=>"",
                "qestatusaprobaciocaja"=>0,
            ],
            "subviewpanelsucursales" => "creditos",
        ]);
        $cxc = $cxcData["num"];

        $cxpData = $this->getsucursalDetallesDataFun([
            "id_sucursal" => $sucursalBalanceGeneral,
            "fechasMain1" => $fechaBalanceGeneral,
            "fechasMain2" => $fechaHastaBalanceGeneral,
            "filtros" => [
                "controlefecQDescripcion"=>"",
                "controlefecSelectCat"=>"",
                "controlefecSelectGeneral"=>1,
                "exacto"=>"",
                "filtronominacargo"=>"",
                "filtronominaq"=>"",
                "itemCero"=>"",
                "num"=>"25",
                "orderBy"=>"desc",
                "orderColumn"=>"descripcion",
                "q"=>"",
                "qcuentasPorPagar"=>"",
                "qestatusaprobaciocaja"=>0,
            ],
            "subviewpanelsucursales" => "cuentasporpagar",
        ]);
        $cxp = $cxpData["sum"];
        
        return [
            "gastos"=>$gastos,

            "sumArrcat" =>$sumArrcat,
            "sumArrcatgeneral" =>$sumArrcatgeneral,
            "sumArringresoegreso" =>$sumArringresoegreso,

            "efectivodolar" =>$dolarbalance,
            "efectivoData" =>$efectivoData,
            "banco" =>$banco,
            "bancoData" =>$bancoData,
            "inventario" =>$inventario,

            "debito" => $debito,
            "efectivo" => $efectivo,
            "transferencia" => $transferencia,
            "biopago" => $biopago,
            "total" => $total,
            "ganancia" =>$ganancia,

            "cierresData" =>$cierreData,
            "cxc" =>$cxc,
            "cxcData" =>$cxcData,
            "cxp" =>$cxp,
            "cxpData" =>$cxpData,
        ];
    }
    function getControldeefectivo($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        $controlefecQ = $filtros["controlefecQDescripcion"];
        $controlefecQCategoria = $filtros["controlefecSelectCat"];

        $controlefecSelectGeneral = $filtros["controlefecSelectGeneral"];

        $cajas = cajas::with(["cat", "sucursal"])->where("tipo", $controlefecSelectGeneral)
        ->when($controlefecQCategoria, function ($q) use ($controlefecQCategoria) {
            $q->where("categoria", $controlefecQCategoria);
        })
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->when($controlefecQ, function ($q) use ($controlefecQ) {
            $q->where("concepto", "LIKE", "%$controlefecQ%");
            //$q->orWhere("montodolar", "LIKE", "%$controlefecQ%");
        })
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])->orderBy("idinsucursal", "desc")
        ->get();



        

        $categorias = [];
        $catGeneral = [];
      

        $cajas->map(function($q) use (&$categorias,&$catGeneral){

            if ($q["cat"]) {
                
                if (isset($categorias[$q["cat"]["id"]])) {
                    $categorias[$q["cat"]["id"]] = [
                        "categoria" => $q["categoria"],
                        "catgeneral" => $q["cat"]["catgeneral"],
                        "nombre" => $q["cat"]["nombre"],
                        "montodolar" => $categorias[$q["cat"]["id"]]["montodolar"] + ($q["montodolar"]),
                        "montobs" => $categorias[$q["cat"]["id"]]["montobs"] + ($q["montobs"]),
                        "montopeso" => $categorias[$q["cat"]["id"]]["montopeso"] + ($q["montopeso"]),
                        "montoeuro" => $categorias[$q["cat"]["id"]]["montoeuro"] + ($q["montoeuro"]),
                    ];
                }else{
                    $categorias[$q["cat"]["id"]] = [
                        "categoria" => $q["categoria"],
                        "catgeneral" => $q["cat"]["catgeneral"],
                        "nombre" => $q["cat"]["nombre"],
                        "montodolar" => $q["montodolar"],
                        "montobs" => $q["montobs"],
                        "montopeso" => $q["montopeso"],
                        "montoeuro" => $q["montoeuro"],
                    ];
                }

                if (isset($catGeneral[$q["cat"]["catgeneral"]])) {
                    $catGeneral[$q["cat"]["catgeneral"]] = [
                        "categoria" => $q["categoria"],
                        "catgeneral" => $q["cat"]["catgeneral"],
                        "nombre" => $q["cat"]["catgeneral"],
                        "montodolar" => $catGeneral[$q["cat"]["catgeneral"]]["montodolar"] + ($q["montodolar"]),
                        "montobs" => $catGeneral[$q["cat"]["catgeneral"]]["montobs"] + ($q["montobs"]),
                        "montopeso" => $catGeneral[$q["cat"]["catgeneral"]]["montopeso"] + ($q["montopeso"]),
                        "montoeuro" => $catGeneral[$q["cat"]["catgeneral"]]["montoeuro"] + ($q["montoeuro"]),
                    ];
                }else{
                    $catGeneral[$q["cat"]["catgeneral"]] = [
                        "categoria" => $q["categoria"],
                        "catgeneral" => $q["cat"]["catgeneral"],
                        "nombre" => $q["cat"]["catgeneral"],
                        "montodolar" => $q["montodolar"],
                        "montobs" => $q["montobs"],
                        "montopeso" => $q["montopeso"],
                        "montoeuro" => $q["montoeuro"],
                    ];
                }
            }

        });

        return [
            "cajas" => $cajas,
            "sum" => [
                "categorias" => $categorias,
                "catgeneral" => $catGeneral,
            ]
        ];
    }

    function getTipoPagoElect($tipo,$fechasMain1,$fechasMain2,$id_sucursal) {
        return puntosybiopagos::with("sucursal")
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->where("tipo",$tipo)
        ->when($id_sucursal, function ($q) use ($id_sucursal) {$q->where("id_sucursal", $id_sucursal);})
        ->sum("monto");
 
    }
    function getPuntosyseriales($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        
        
        $data = puntosybiopagos::with("sucursal")
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
        ->when($id_sucursal, function ($q) use ($id_sucursal) {$q->where("id_sucursal", $id_sucursal);});

        $p1 = $this->getTipoPagoElect("p1",$fechasMain1,$fechasMain2,$id_sucursal);
        $p2 = $this->getTipoPagoElect("p2",$fechasMain1,$fechasMain2,$id_sucursal);
        $transferencia = $this->getTipoPagoElect("Transferencia",$fechasMain1,$fechasMain2,$id_sucursal);
        $biopago = $this->getTipoPagoElect("BioPago",$fechasMain1,$fechasMain2,$id_sucursal);
        
        return [
            "data" => $data->orderBy("tipo","desc")->get(),
            "suma" => [
                "p1" => $p1,  
                "p2" => $p2, 
                "Transferencia" => $transferencia, 
                "BioPago" => $biopago, 
            ]
            ];
    }

    function getNominasSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {

        $filtronominaq = $filtros["filtronominaq"];
        $filtronominacargo = $filtros["filtronominacargo"];

        $data = nomina::with(["sucursal","cargo","pagos"])
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("nominasucursal", $id_sucursal);
            })
            ->when($filtronominacargo, function ($q) use ($filtronominacargo) {
                $q->where("nominacargo", $filtronominacargo);
            })
            ->when($filtronominaq, function ($q) use ($filtronominaq) {
                $q
                    ->orwhere("nominanombre", "LIKE", $filtronominaq."%")
                    ->orwhere("nominacedula", "LIKE", $filtronominaq."%")
                    ->orwhere("nominatelefono", "LIKE", $filtronominaq."%");
            })
            ->get();
        
            return [
                "data" => $data->map(function ($item) {
                    $nom = nomina::where("nominasucursal", $item->nominasucursal);
                    
                    $item->sucursaldesc =  " (".$nom->count().") ".$item->sucursal->codigo;
                    $item->cargodesc = " (".$nom->where("nominacargo",$item->nominacargo)->count().") ".$item->cargo->cargosdescripcion;
                    $item->bono = $item->cargo->cargossueldo;
                    return $item;
                })
                ->sortByDesc("cargo.cargossueldo")
                ->groupBy(["sucursaldesc","cargodesc"]),
                
                "sum" => $data->count()
            ];
    }
}
