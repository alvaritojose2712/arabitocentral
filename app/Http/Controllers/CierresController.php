<?php

namespace App\Http\Controllers;
use App\Models\catcajas;
ini_set('memory_limit', '4095M');
set_time_limit(60000000);

use App\Models\bancos_list;
use App\Models\bancos;
use App\Models\cierres;
use App\Models\cajas;
use App\Models\comovamos;
use App\Models\creditos;
use App\Models\inventario_sucursal;
use App\Models\inventario_sucursal_estadisticas;
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
        $sendestadisticasVentareq = $req->sendestadisticasVenta;
        $sendestadisticasVenta = json_decode(gzuncompress(base64_decode($sendestadisticasVentareq)),true);
        //return $sendestadisticasVenta;


        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];
        $today = (new NominaController)->today();
        
        garantias::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        fallas::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        cajas::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        puntosybiopagos::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->whereNull("fecha_liquidacion")->delete();
        cierres::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        inventario_sucursal_estadisticas::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
        

        $sendInventarioCt = (new InventarioSucursalController)->sendInventarioCt($req->sendInventarioCt, $id_sucursal);
        $sendGarantias = (new GarantiasController)->sendGarantias($req->sendGarantias, $id_sucursal);
        $sendFallas = (new FallasController)->sendFallas($req->sendFallas, $id_sucursal);
        $setCierreFromSucursalToCentral = (new CierresController)->setCierreFromSucursalToCentral($req->setCierreFromSucursalToCentral, $id_sucursal);
        $setEfecFromSucursalToCentral = (new CajasController)->setEfecFromSucursalToCentral($req->setEfecFromSucursalToCentral, $id_sucursal);
        $sendestadisticasVenta = (new InventarioSucursalEstadisticasController)->sendestadisticasVenta($sendestadisticasVenta, $id_sucursal);

        $sendCreditos = (new CreditosController)->sendCreditos($req->sendCreditos, $id_sucursal);

        if (!isset($setEfecFromSucursalToCentral["last"])) {return "setEfecFromSucursalToCentral: ".$setEfecFromSucursalToCentral;}
        if (!isset($setCierreFromSucursalToCentral["last"])) {return "setCierreFromSucursalToCentral: ".$setCierreFromSucursalToCentral;}
        if (!isset($sendGarantias["last"])) {return "sendGarantias: ".$sendGarantias;}
        if (!isset($sendFallas["last"])) {return "sendFallas: ".$sendFallas;}
        if (!isset($sendCreditos["last"])) {return "sendCreditos: ".$sendCreditos;}
        if (!isset($sendestadisticasVenta["last"])) {return "sendestadisticasVenta: ".$sendestadisticasVenta;}

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
            "id_last_estadisticas" => $sendestadisticasVenta["last"],
            
        ]);
        return [
            $sendInventarioCt,
            $sendGarantias["msj"],
            $sendFallas["msj"],
            $setCierreFromSucursalToCentral["msj"],
            $setEfecFromSucursalToCentral["msj"],
            $sendCreditos["msj"],
            $sendestadisticasVenta["msj"],
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
                        $id_banco = bancos_list::where("codigo",$lote["banco"])->first()->id;
                        $loteSql = puntosybiopagos::updateOrCreate([
                            "fecha" => $lote["fecha"],
                            "id_usuario" => $lote["id_usuario"],
                            "id_sucursal" => $id_origen,
                            "tipo" => $lote["tipo"],
                        ], [
                            "loteserial" => $lote["lote"],
                            "monto" => $lote["monto"],
                            "banco" => $lote["banco"],
                            "id_banco" => $id_banco,
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
    function dividir($dividendo,$divisor) {
        $dividendo = floatval($dividendo);
        
        if (intval($dividendo)==0) {
            return 0;
        }else{
            if ($divisor==0) {
                return 0;
            }else{
                return floatval($dividendo)/floatval($divisor);

            }

        }
       

    }
    function getCuadreGeneral(Request $req) {
        $id_sucursal = $req->sucursalqcuadregeneral;
        $fechasMain1 = $req->fechadesdeqcuadregeneral;
        $fechasMain2 = $req->fechahastaqcuadregeneral;

        if (!$fechasMain1) {
            return "Sin fecha Seleccionada!";
        }
        

        $debito = [];
        $sum_debito = 0;
        $sum_debito_dolar = 0;
        $efectivo = [];
        $sum_efectivo = 0;
        $transferencia = [];
        $sum_transferencia = 0;
        $sum_transferencia_dolar = 0;
        $caja_biopago = [];
        $sum_caja_biopago = 0;
        $sum_caja_biopago_dolar = 0;
        
        $sum_caja_regis_inicial = 0;
        $sum_caja_chica_inicial = 0;
        $sum_caja_fuerte_inicial = 0;



        
        $gastos_fijos = [];
        $sum_gastos_fijos = 0;
        $gastos_variables = [];
        $sum_gastos_variables = 0;
        $pago_proveedores = [];
        $sum_pago_proveedores = 0;

        $caja_inicial = [];
        $sum_caja_inicial = 0;

        $caja_inicial_banco = [];
        $sum_caja_inicial_banco = 0;
        $sum_caja_inicial_banco_dolar = 0;

        $sum_banco_pagoproveedor_egreso =  0;
        $sum_efectivo_pagoproveedor_egreso =  0;

        $sum_banco_gasto_fijovar =  0;
        $sum_efectivo_gasto_fijovar =  0;


        
        
        
        $tasas = cierres::where("fecha", $fechasMain1)->orderBy("fecha","desc")->first();
        if (!$tasas) {
            return "Sin Cierres registrados";
        }
        $bs = $tasas->tasa;
        $cop = $tasas->tasacop;


        $cierres = cierres::with("sucursal")
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->where("fecha", $fechasMain1)
        ->orderBy("fecha","desc")
        ->get()
        ->map(function($q) use ($fechasMain1, &$caja_inicial, &$sum_caja_inicial, &$sum_caja_regis_inicial,&$sum_caja_chica_inicial,&$sum_caja_fuerte_inicial){
            $caja_inicial_suc = cierres::with("sucursal")->where("id_sucursal",$q->id_sucursal)->where("fecha","<",$fechasMain1)->orderBy("fecha","desc")->first();
            $bs = $caja_inicial_suc["tasa"];
            $cop = $caja_inicial_suc["tasacop"];
            
            $caja_chica = cajas::where("id_sucursal",$q->id_sucursal)->where("tipo",0)->where("fecha","<",$fechasMain1)->orderBy("fecha","desc")->orderBy("id","desc")->first();
            $caja_fuerte = cajas::where("id_sucursal",$q->id_sucursal)->where("tipo",1)->where("fecha","<",$fechasMain1)->orderBy("fecha","desc")->orderBy("id","desc")->first();
            
            $sum_caja_registradora = $caja_inicial_suc["dejar_dolar"]+$this->dividir($caja_inicial_suc["dejar_peso"],$cop)+$this->dividir($caja_inicial_suc["dejar_bss"],$bs);
            $sum_caja_chica = $caja_chica["dolarbalance"]+ $this->dividir($caja_chica["bsbalance"],$bs)+ $this->dividir($caja_chica["pesobalance"],$cop)+$caja_chica["eurobalance"];
            $sum_caja_fuerte = $caja_fuerte["dolarbalance"]+ $this->dividir($caja_fuerte["bsbalance"],$bs)+ $this->dividir($caja_fuerte["pesobalance"],$cop)+$caja_fuerte["eurobalance"];

            $sum_cajas = $sum_caja_registradora+$sum_caja_fuerte+$sum_caja_chica;
            
            $caja_inicial[$caja_inicial_suc["sucursal"]["codigo"]] = [
                "caja_registradora" => [
                    "dolar" => $caja_inicial_suc["dejar_dolar"],
                    "peso" => $caja_inicial_suc["dejar_peso"],
                    "bs" => $caja_inicial_suc["dejar_bss"],
                    "euro" => 0,
                    "total_dolar" => $sum_caja_registradora,
                ],
                "caja_fuerte" => [
                    "dolar" => $caja_fuerte["dolarbalance"],
                    "bs" => $caja_fuerte["bsbalance"],
                    "peso" => $caja_fuerte["pesobalance"],
                    "euro" => $caja_fuerte["eurobalance"],
                    "total_dolar" => $sum_caja_fuerte,
                ],
                "caja_chica" => [
                    "dolar" => $caja_chica["dolarbalance"],
                    "bs" => $caja_chica["bsbalance"],
                    "peso" => $caja_chica["pesobalance"],
                    "euro" => $caja_chica["eurobalance"],
                    "total_dolar" => $sum_caja_chica,
                ],
                "sum_cajas" => $sum_cajas,
            ];
            $sum_caja_inicial += $sum_cajas;
            $sum_caja_regis_inicial +=  $sum_caja_registradora;
            $sum_caja_chica_inicial +=  $sum_caja_chica;
            $sum_caja_fuerte_inicial +=  $sum_caja_fuerte;
            return $q;
        });

        foreach ($cierres as $i => $c) {
            $codigo = $c->sucursal->codigo;
            $efectivo[$codigo] = isset($efectivo[$codigo])?$efectivo[$codigo]+$c->efectivo: $c->efectivo;
            $sum_efectivo += $c->efectivo;
            //////////////////
            /* $caja_biopago[$codigo] = isset($caja_biopago[$codigo])?$caja_biopago[$codigo]+$c->caja_biopago: $c->caja_biopago;
            $sum_caja_biopago += $c->caja_biopago;
            $sum_caja_biopago_dolar += $this->dividir($c->caja_biopago,$bs); */


            $caja_biopagos = puntosybiopagos::where("id_sucursal",$c->id_sucursal)->where("origen",1)->where("fecha_liquidacion",$fechasMain1)->where("tipo","LIKE","BIOPAGO%")->get();
            $sum_all_biopago = $caja_biopagos->sum("monto");
            $bancos_caja_biopago = [];
            foreach ($caja_biopagos as $item_caja_biopago) {
                $bancos_caja_biopago[$item_caja_biopago["banco"]] = [
                    "bs" => isset($bancos_caja_biopago[$item_caja_biopago["banco"]])? $bancos_caja_biopago[$item_caja_biopago["banco"]]["bs"] + $item_caja_biopago["monto_liquidado"]: $item_caja_biopago["monto_liquidado"],
                    "dolar" => isset($bancos_caja_biopago[$item_caja_biopago["banco"]])? $bancos_caja_biopago[$item_caja_biopago["banco"]]["dolar"] + $this->dividir($item_caja_biopago["monto_liquidado"],$bs): $this->dividir($item_caja_biopago["monto_liquidado"],$bs),
                ]; 
            }
            $caja_biopago[$codigo]["sum_caja_biopago"] = $sum_all_biopago;
            $caja_biopago[$codigo]["sum_caja_biopago_dolar"] = $this->dividir($sum_all_biopago,$bs);
            $sum_caja_biopago += $sum_all_biopago;
            $sum_caja_biopago_dolar += $this->dividir($sum_all_biopago,$bs);
            $caja_biopago[$codigo]["bancos_caja_biopago"] = $bancos_caja_biopago;


            /////////////////
            $debitos = puntosybiopagos::where("id_sucursal",$c->id_sucursal)->where("origen",1)->where("fecha_liquidacion",$fechasMain1)->where("tipo","LIKE","PUNTO%")->get();
            $bancos_debito = [];
            foreach ($debitos as $item_punto) {
                $bancos_debito[$item_punto["banco"]] = [
                    "bs" => isset($bancos_debito[$item_punto["banco"]])? $bancos_debito[$item_punto["banco"]]["bs"] + $item_punto["monto_liquidado"]: $item_punto["monto_liquidado"],
                    "dolar" => isset($bancos_debito[$item_punto["banco"]])? $bancos_debito[$item_punto["banco"]]["dolar"] + $this->dividir($item_punto["monto_liquidado"],$bs): $this->dividir($item_punto["monto_liquidado"],$bs),
                ]; 
            }
            $debito[$codigo]["sum_debitos"] = $debitos->sum("monto_liquidado");
            $debito[$codigo]["sum_debitos_dolar"] = $this->dividir($debitos->sum("monto_liquidado"),$bs);
            $sum_debito += $debitos->sum("monto_liquidado");
            $sum_debito_dolar += $this->dividir($debitos->sum("monto_liquidado"),$bs);
            $debito[$codigo]["bancos_debito"] = $bancos_debito;
            ///////////////
            $transferencias = puntosybiopagos::where("id_sucursal",$c->id_sucursal)->where("origen",1)->where("fecha_liquidacion",$fechasMain1)->where("tipo","LIKE","Transferencia%")->get();
            $bancos_transferencias = [];
            foreach ($transferencias as $item_trans) {
                $bancos_transferencias[$item_trans["banco"]] = [
                    "bs" => isset($bancos_transferencias[$item_trans["banco"]])? $bancos_transferencias[$item_trans["banco"]]["bs"] + $item_trans["monto"]: $item_trans["monto"],
                    "dolar" => isset($bancos_transferencias[$item_trans["banco"]])? $bancos_transferencias[$item_trans["banco"]]["dolar"] + $this->dividir($item_trans["monto"],$bs): $this->dividir($item_trans["monto"],$bs)
                ]; 
            }
            $transferencia[$codigo]["sum_transferencias"] = $transferencias->sum("monto");
            $transferencia[$codigo]["sum_transferencias_dolar"] = $this->dividir($transferencias->sum("monto"),$bs);
            $sum_transferencia += $transferencias->sum("monto");
            $sum_transferencia_dolar += $this->dividir($transferencias->sum("monto"),$bs);
            $transferencia[$codigo]["bancos_transferencias"] = $bancos_transferencias;

        }
        $pago_proveedoresFun = (new CuentasporpagarController)->selectCuentaPorPagarProveedorDetallesFun([
            "fechasMain1" => $fechasMain1,
            "fechasMain2" => $fechasMain1,

            "categoriacuentasPorPagarDetalles" => "",
            "cuentaporpagarAprobado" => 1,
            "id_facts_force" => null,
            "id_proveedor" => "",
            "numcuentasPorPagarDetalles" => "",
            "OrdercuentasPorPagarDetalles" => "desc",
            "qCampocuentasPorPagarDetalles" => "updated_at",
            "qcuentasPorPagarDetalles" => "",
            "qcuentasPorPagarTipoFact" => "abonos",
            "sucursalcuentasPorPagarDetalles" => $id_sucursal,
            "tipocuentasPorPagarDetalles" => "",
            "type" => "buscar",
        ]);
        $pago_proveedoresGroup = collect($pago_proveedoresFun["detalles"])->groupBy(["proveedor.descripcion"]);
        $sum_pago_proveedores = $pago_proveedoresFun["balance"];


        foreach ($pago_proveedoresGroup as $desc_proveedor => $dataproveedor) {
            array_push($pago_proveedores,[
                "descripcion" => $desc_proveedor,
                "sum" => $dataproveedor->sum("monto"),
                "data" => $dataproveedor
            ]);

            $sum_banco_pagoproveedor_egreso += $dataproveedor->sum("pago_banco");
            $sum_efectivo_pagoproveedor_egreso +=  $dataproveedor->sum("pago_efectivo");
        }


        $gastosFun = collect(array_filter((new PuntosybiopagosController)
        ->getGastosFun([
            "gastosQ"=>"",
            "gastosQFecha"=>$fechasMain1,
            "gastosQFechaHasta"=>$fechasMain1,
            "gastosQCategoria"=>"",
            "catgeneral"=>"",
            "ingreso_egreso"=>"",
            "typecaja"=>"",
            "gastosorder"=>"desc",
            "gastosfieldorder"=>"variable_fijo",
        ])["data"],function($filter) {
            return $filter["cat"]["catgeneral"]==2||$filter["cat"]["catgeneral"]==3; 
        }))->groupBy(["cat.variable_fijo","sucursal.codigo","cat.id"]);

        $distgastosFun = collect(array_filter((new PuntosybiopagosController)
        ->getGastosFun([
            "gastosQ"=>"",
            "gastosQFecha"=>$fechasMain1,
            "gastosQFechaHasta"=>$fechasMain1,
            "gastosQCategoria"=>"",
            "catgeneral"=>"",
            "ingreso_egreso"=>"",
            "typecaja"=>"",
            "gastosorder"=>"desc",
            "gastosfieldorder"=>"variable_fijo",
        ])["data"],function($filter) {
            return $filter["cat"]["catgeneral"]!=2&&$filter["cat"]["catgeneral"]!=3; 
        }))->groupBy(["cat.variable_fijo","sucursal.codigo","cat.id"]);


        $gastos = [];
        $mov_dist_gastos = [];
        foreach ($gastosFun as $id_var_fijo => $bysucursal) {
            $gastos[$id_var_fijo] = [
                "sum" => 0,
                "data" => [],
            ];
            $sum_gastos_var_fijo = 0;
            foreach ($bysucursal as $codigo_sucursal => $cats) {
                $sum_gastos_sucursal = 0;
                $gastos[$id_var_fijo]["data"][$codigo_sucursal] = [
                    "sum" => 0,
                    "data" => []
                ];

                foreach ($cats as $id_cat => $values) {
                    $gastos[$id_var_fijo]["data"][$codigo_sucursal]["data"][$id_cat] = [
                        "sum" => $values->sum("montodolar"),
                        "data" => $values 
                    ];
                    $sum_gastos_sucursal += $values->sum("montodolar");
                    $sum_gastos_var_fijo += $values->sum("montodolar");

                    $sum_banco_gasto_fijovar += $values->sum("pago_banco");
                    $sum_efectivo_gasto_fijovar += $values->sum("pago_efectivo");
                }

                $gastos[$id_var_fijo]["data"][$codigo_sucursal]["sum"] = $sum_gastos_sucursal;
            }

            $gastos[$id_var_fijo]["sum"] = $sum_gastos_var_fijo; 
        }
        foreach ($distgastosFun as $id_var_fijo => $bysucursal) {
            $mov_dist_gastos[$id_var_fijo] = [
                "sum" => 0,
                "data" => [],
            ];
            $sum_gastos_var_fijo = 0;
            foreach ($bysucursal as $codigo_sucursal => $cats) {
                $sum_gastos_sucursal = 0;
                $mov_dist_gastos[$id_var_fijo]["data"][$codigo_sucursal] = [
                    "sum" => 0,
                    "data" => []
                ];

                foreach ($cats as $id_cat => $values) {
                    $mov_dist_gastos[$id_var_fijo]["data"][$codigo_sucursal]["data"][$id_cat] = [
                        "sum" => $values->sum("montodolar"),
                        "data" => $values 
                    ];
                    $sum_gastos_sucursal += $values->sum("montodolar");
                    $sum_gastos_var_fijo += $values->sum("montodolar");
                }

                $mov_dist_gastos[$id_var_fijo]["data"][$codigo_sucursal]["sum"] = $sum_gastos_sucursal;
            }

            $mov_dist_gastos[$id_var_fijo]["sum"] = $sum_gastos_var_fijo; 
        }
        $gastos_fijos = $gastos[1]["data"];
        $sum_gastos_fijos = $gastos[1]["sum"];
        $gastos_variables = $gastos[0]["data"];
        $sum_gastos_variables = $gastos[0]["sum"];

        $blist = bancos_list::where("codigo","<>","EFECTIVO")->orderBy("codigo","asc")->get();

        foreach ($blist as $banco) {
            $b = bancos::where("banco",$banco->codigo)->where("fecha","<",$fechasMain1)->orderBy("fecha","desc")->first("saldo");
            $saldo = $b?$b->saldo:0;
            
            array_push($caja_inicial_banco, [
                "banco"=> $banco->codigo,
                "saldo" => $banco->moneda=="dolar"? 0: $saldo,
                "saldo_dolar" => $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs),
            ]);
            $sum_caja_inicial_banco += $banco->moneda=="bs"? $saldo: 0;
            $sum_caja_inicial_banco_dolar += $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs);
        }

        $total_ingresos = $sum_debito_dolar+$sum_efectivo+$sum_transferencia_dolar+$sum_caja_biopago_dolar;
        $total_egresos = abs($sum_gastos_fijos)+abs($sum_gastos_variables)+abs($sum_pago_proveedores);
        $total_caja_inicial = $sum_caja_inicial+$sum_caja_inicial_banco_dolar;


        $sum_banco_ingreso =  $sum_debito_dolar+$sum_transferencia_dolar+$sum_caja_biopago_dolar;
        $sum_efectivo_ingreso =  $sum_efectivo;

        $total_banco = ($sum_caja_inicial+$sum_banco_ingreso) - (abs($sum_banco_gasto_fijovar)+abs($sum_banco_pagoproveedor_egreso));
        $total_efectivo = ($sum_caja_inicial_banco_dolar+$sum_efectivo_ingreso) - (abs($sum_efectivo_gasto_fijovar)+abs($sum_efectivo_pagoproveedor_egreso));
        
        
        
        return [
            "mov_dist_gastos" => $mov_dist_gastos,
            "debito" => $debito,
            "sum_debito" => $sum_debito,
            "efectivo" => $efectivo,
            "sum_efectivo" => $sum_efectivo,
            "transferencia" => $transferencia,
            "sum_transferencia" => $sum_transferencia,
            "caja_biopago" => $caja_biopago,
            "sum_caja_biopago" => $sum_caja_biopago,

            "sum_caja_regis" => $sum_caja_regis_inicial,
            "sum_caja_chica" => $sum_caja_chica_inicial,
            "sum_caja_fuerte" => $sum_caja_fuerte_inicial,

            "sum_debito_dolar" => $sum_debito_dolar,
            "sum_transferencia_dolar" => $sum_transferencia_dolar,
            "sum_caja_biopago_dolar" => $sum_caja_biopago_dolar,
            
            "gastos_fijos" => $gastos_fijos,
            "sum_gastos_fijos" => $sum_gastos_fijos,
            
            "gastos_variables" => $gastos_variables,
            "sum_gastos_variables" => $sum_gastos_variables,
            
            "pago_proveedores" => $pago_proveedores,
            "sum_pago_proveedores" => $sum_pago_proveedores,
            "caja_inicial" => $caja_inicial,
            "sum_caja_inicial" => $sum_caja_inicial,

            "caja_inicial_banco" => $caja_inicial_banco,
            "sum_caja_inicial_banco" => $sum_caja_inicial_banco,
            "sum_caja_inicial_banco_dolar" => $sum_caja_inicial_banco_dolar,


            "total_ingresos" => $total_ingresos,
            "total_egresos" => $total_egresos,
            "total_caja_inicial" => $total_caja_inicial,
            "cuantodebotener" => ($total_caja_inicial+$total_ingresos)-$total_egresos,



            "sum_banco_pagoproveedor_egreso" => $sum_banco_pagoproveedor_egreso,
            "sum_efectivo_pagoproveedor_egreso" => $sum_efectivo_pagoproveedor_egreso,

            "sum_banco_ingreso" => $sum_banco_ingreso,
            "sum_efectivo_ingreso" => $sum_efectivo_ingreso,

            "sum_banco_gasto_fijovar" => $sum_banco_gasto_fijovar,
            "sum_efectivo_gasto_fijovar" => $sum_efectivo_gasto_fijovar,

            "total_banco" => $total_banco,
            "total_efectivo" => $total_efectivo,

            

        ];


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

    function getTasa() {
        $bsq = cierres::orderBy("id","desc")->first(["tasa","tasacop"]);
        $bs = 1;
        $cop = 1;
        if ($bsq) {
            $bs = $bsq->tasa;
            $cop = $bsq->tasacop;
        }
        return [
            "bs" => $bs,
            "cop" => $cop,
        ];
    }
    function getBalanceGeneral(Request $req) {
        $usuario = session("usuario");

        /* if ($usuario!="ao"&&$usuario!="omarelh") {
           return ;
        }  */

        $sucursalBalanceGeneral = $req->sucursalBalanceGeneral;
        $fechaBalanceGeneral = $req->fechaBalanceGeneral;
        $fechaHastaBalanceGeneral = $req->fechaHastaBalanceGeneral;

        $cuantotengobanco = floatval($req->cuantotengobanco)? floatval($req->cuantotengobanco): 0;
        $cuantotengoefectivo = floatval($req->cuantotengoefectivo)? floatval($req->cuantotengoefectivo): 0;

        if (!$fechaBalanceGeneral || !$fechaHastaBalanceGeneral) {
            return ["Seleccione ambas Fechas"];
        }
        $bs = $this->getTasa()["bs"];
        $cop = $this->getTasa()["cop"];


        

        $sumArrcat = [];
        $sumArrcatgeneral = [];
        $sumArringresoegreso = [];
        $sumArrvariablefijo = [];
        
        $fdi = array_filter((new PuntosybiopagosController)
        ->getGastosFun([
            "gastosQ"=>"",
            "gastosQFecha"=>$fechaBalanceGeneral,
            "gastosQFechaHasta"=>$fechaHastaBalanceGeneral,
            "gastosQCategoria"=>"41",
            "catgeneral"=>"",
            "ingreso_egreso"=>"",
            "typecaja"=>"",
            "gastosorder"=>"desc",
            "gastosfieldorder"=>"variable_fijo",
        ])["data"],function($filter) {
            return $filter["cat"]["id"]!=40; //No es PAGO A PROVEEDOR
        });
        $sumFDI = abs(collect($fdi)->sum("montodolar"));


        $gastosFun = array_filter((new PuntosybiopagosController)
        ->getGastosFun([
            "gastosQ"=>"",
            "gastosQFecha"=>$fechaBalanceGeneral,
            "gastosQFechaHasta"=>$fechaHastaBalanceGeneral,
            "gastosQCategoria"=>"",
            "catgeneral"=>"",
            "ingreso_egreso"=>"",
            "typecaja"=>"",
            "gastosorder"=>"desc",
            "gastosfieldorder"=>"variable_fijo",
        ])["data"],function($filter) {
            return $filter["cat"]["id"]!=40 && ($filter["cat"]["catgeneral"]==2||$filter["cat"]["catgeneral"]==3); //No es PAGO A PROVEEDOR
        });
        foreach ($gastosFun as $gastoi => $gasto) {
            $ingresoegreso_key = $gasto["ingreso_egreso"];
            $cat_key = $gasto["categoria"];
            $catgeneral_key = $gasto["catgeneral"];
            $variablefijo_key = $gasto["variable_fijo"];



            $monto =  (isset($gasto["montodolar"])?$gasto["montodolar"]:0)+((isset($gasto["montobs"])?$gasto["montobs"]:0)/$bs)+((isset($gasto["montopeso"])?$gasto["montopeso"]:0)/$cop);

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

            if (array_key_exists($catgeneral_key, $sumArrvariablefijo)) {
                if (array_key_exists($variablefijo_key, $sumArrvariablefijo[$catgeneral_key])) {
                    $sumArrvariablefijo[$catgeneral_key][$variablefijo_key]["sumdolar"] = $sumArrvariablefijo[$catgeneral_key][$variablefijo_key]["sumdolar"] + $monto;  
                }else{
                    $sumArrvariablefijo[$catgeneral_key][$variablefijo_key] = [
                        "sumdolar" => $monto,
                    ];    
                }
            }else{
                $sumArrvariablefijo[$catgeneral_key][$variablefijo_key] = [
                    "sumdolar" => $monto,
                ];
            }
            $gastosFun[$gastoi]["montofull"] = $monto;
        }
        $gastos = collect($gastosFun)->groupBy(["ingreso_egreso","catgeneral","variable_fijo","categoria"]);



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

        $diaAntesPrimerDia = strtotime('-1 days', strtotime($fechaBalanceGeneral));
        $diaAntesPrimerDia = date('Y-m-d' , $diaAntesPrimerDia);
        $cierreDataPrimer = $this->getsucursalDetallesDataFun([
            "id_sucursal" => $sucursalBalanceGeneral,
            "fechasMain1" => $diaAntesPrimerDia,
            "fechasMain2" => $diaAntesPrimerDia,
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
        $debitoAntesPrimerDia = $cierreDataPrimer["sum"]["debito_clean"];
        $biopagoAntesPrimerDia = $cierreDataPrimer["sum"]["biopago_clean"];


        $cierreDataUltimo = $this->getsucursalDetallesDataFun([
            "id_sucursal" => $sucursalBalanceGeneral,
            "fechasMain1" => $fechaHastaBalanceGeneral,
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
        $debitoUltimoDia = $cierreDataUltimo["sum"]["debito_clean"];
        $biopagoUltimoDia = $cierreDataUltimo["sum"]["biopago_clean"];

        
        
        $inventario = $cierreData["sum"]["inventariobase_clean"];
        
        $efectivo = $cierreData["sum"]["efectivo_clean"];
        $debito = ($cierreData["sum"]["debito_clean"])+$debitoAntesPrimerDia-$debitoUltimoDia;
        $biopago = $cierreData["sum"]["biopago_clean"]+$biopagoAntesPrimerDia-$biopagoUltimoDia;
        $transferencia = $cierreData["sum"]["transferencia_clean"];
        $total = $cierreData["sum"]["total_clean"]+($debitoAntesPrimerDia-$debitoUltimoDia)+($biopagoAntesPrimerDia-$biopagoUltimoDia);
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

        

        $pagoproveedor = (new CuentasporpagarController)->selectCuentaPorPagarProveedorDetallesFun([
            "fechasMain1" => $fechaBalanceGeneral,
            "fechasMain2" => $fechaHastaBalanceGeneral,

            "categoriacuentasPorPagarDetalles" => "",
            "cuentaporpagarAprobado" => 1,
            "id_facts_force" => null,
            "id_proveedor" => "",
            "numcuentasPorPagarDetalles" => "",
            "OrdercuentasPorPagarDetalles" => "desc",
            "qCampocuentasPorPagarDetalles" => "updated_at",
            "qcuentasPorPagarDetalles" => "",
            "qcuentasPorPagarTipoFact" => "abonos",
            "sucursalcuentasPorPagarDetalles" => $sucursalBalanceGeneral,
            "tipocuentasPorPagarDetalles" => "",
            "type" => "buscar",
        ]);
        ////////
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
        ////////

        $pagoproveedorBanco = collect($pagoproveedor["detalles"])->where("metodo","Transferencia");
        
        $sumPagoProveedorEfectivo = collect($pagoproveedor["detalles"])->where("metodo","<>","Transferencia")->sum("monto");
        $sumPagoProveedorBanco = 0;
        $sumPagoProveedorBancoReal = 0;

        foreach ($pagoproveedorBanco as $i => $pagoProveedorBancoVal) {

            $montobs1 = $this->dividir($pagoProveedorBancoVal["montobs1"],$pagoProveedorBancoVal["tasabs1"]);
            $montobs2 = $this->dividir($pagoProveedorBancoVal["montobs2"],$pagoProveedorBancoVal["tasabs2"]);
            $montobs3 = $this->dividir($pagoProveedorBancoVal["montobs3"],$pagoProveedorBancoVal["tasabs3"]);
            $montobs4 = $this->dividir($pagoProveedorBancoVal["montobs4"],$pagoProveedorBancoVal["tasabs4"]);
            $montobs5 = $this->dividir($pagoProveedorBancoVal["montobs5"],$pagoProveedorBancoVal["tasabs5"]);
            $sumPagoProveedorBanco += $montobs1+$montobs2+$montobs3+$montobs4+$montobs5;


            $tasas = cierres::where("fecha","<=", $pagoProveedorBancoVal["fechaemision"])->orderBy("fecha","desc")->first();
            $bs = $tasas->tasa;
            $cop = $tasas->tasacop;

            
            $bs1 = 1;
            $bs2 = 1;
            $bs3 = 1;
            $bs4 = 1;
            $bs5 = 1;
            $id_metodobs1 = bancos_list::find($pagoProveedorBancoVal["id_metodobs1"]);
            if ($id_metodobs1) {
                if ($id_metodobs1->moneda=="bs") {
                    $bs1 = $bs;
                }else{
                    $bs1 = 1;
                }
            } 
            $id_metodobs2 = bancos_list::find($pagoProveedorBancoVal["id_metodobs2"]);
            if ($id_metodobs2) {
                if ($id_metodobs2->moneda=="bs") {
                    $bs2 = $bs;
                }else{
                    $bs2 = 1;
                }
            } 
            $id_metodobs3 = bancos_list::find($pagoProveedorBancoVal["id_metodobs3"]);
            if ($id_metodobs3) {
                if ($id_metodobs3->moneda=="bs") {
                    $bs3 = $bs;
                }else{
                    $bs3 = 1;
                }
            } 
            $id_metodobs4 = bancos_list::find($pagoProveedorBancoVal["id_metodobs4"]);
            if ($id_metodobs4) {
                if ($id_metodobs4->moneda=="bs") {
                    $bs4 = $bs;
                }else{
                    $bs4 = 1;
                }
            } 
            $id_metodobs5 = bancos_list::find($pagoProveedorBancoVal["id_metodobs5"]);
            if ($id_metodobs5) {
                if ($id_metodobs5->moneda=="bs") {
                    $bs5 = $bs;
                }else{
                    $bs5 = 1;
                }
            } 

            $montobsReal1 = $this->dividir($pagoProveedorBancoVal["montobs1"], $bs1);
            $montobsReal2 = $this->dividir($pagoProveedorBancoVal["montobs2"], $bs2);
            $montobsReal3 = $this->dividir($pagoProveedorBancoVal["montobs3"], $bs3);
            $montobsReal4 = $this->dividir($pagoProveedorBancoVal["montobs4"], $bs4);
            $montobsReal5 = $this->dividir($pagoProveedorBancoVal["montobs5"], $bs5);
            $sumPagoProveedorBancoReal += $montobsReal1+$montobsReal2+$montobsReal3+$montobsReal4+$montobsReal5;
        }
        $pagoProveedorBruto = abs($sumPagoProveedorBancoReal) + abs($sumPagoProveedorEfectivo);
        $perdidaPagoProveedor = $pagoProveedorBruto - abs($pagoproveedor["balance"]);

        $gastosfijosSum = isset($sumArrvariablefijo[2])? $sumArrvariablefijo[2][1]["sumdolar"]: 0;
        $gastosvariablesSum = isset($sumArrvariablefijo[2])? $sumArrvariablefijo[2][0]["sumdolar"]: 0;

        $gastosGeneralesfijosSum = isset($sumArrvariablefijo[3])? (isset($sumArrvariablefijo[3][1])? $sumArrvariablefijo[3][1]["sumdolar"]:0): 0;
        $gastosGeneralesvariablesSum = isset($sumArrvariablefijo[3])? (isset($sumArrvariablefijo[3][0])? $sumArrvariablefijo[3][0]["sumdolar"]:0): 0;

        $sumGastos = abs($gastosfijosSum) + abs($gastosvariablesSum) + abs($gastosGeneralesfijosSum) + abs($gastosGeneralesvariablesSum);
        $gananciaNeta = $ganancia-$sumGastos;
        
        $porcevbrutanum = round( $this->dividir((abs($sumGastos)*100),($total)),2);
        $porcegbrutanum = round( $this->dividir((abs($sumGastos)*100),($ganancia)),2);
        $porcegnetanum = round( $this->dividir((abs($sumGastos)*100),($gananciaNeta)),2);
        $blist = bancos_list::where("codigo","<>","EFECTIVO")->get();
        $sucursales = sucursal::when($sucursalBalanceGeneral, function ($q) use ($sucursalBalanceGeneral) {
            $q->where("id", $sucursalBalanceGeneral);
        })
        ->whereNotIn("id",[13,17])
        ->get();


        /// CAJA INICIAL
            $caja_inicial = [];
            $caja_inicial_banco = [];
            $sum_caja_inicial_banco = 0;
            $sum_caja_inicial_banco_dolar = 0;
            $sum_caja_inicial = 0;
            $sum_caja_regis_inicial = 0;
            $sum_caja_chica_inicial = 0;
            $sum_caja_fuerte_inicial = 0;
            foreach($sucursales as $key => $q){
                $caja_inicial_suc = cierres::with("sucursal")->where("id_sucursal",$q->id)->where("fecha","<",$fechaBalanceGeneral)->orderBy("fecha","desc")->first();

                if ($caja_inicial_suc) {
                    $caja_chica = cajas::where("id_sucursal",$q->id)->where("tipo",0)->where("fecha","<",$fechaBalanceGeneral)->orderBy("fecha","desc")->orderBy("id","desc")->first();
                    $caja_fuerte = cajas::where("id_sucursal",$q->id)->where("tipo",1)->where("fecha","<",$fechaBalanceGeneral)->orderBy("fecha","desc")->orderBy("id","desc")->first();
                    $bs = $caja_inicial_suc["tasa"];
                    $cop = $caja_inicial_suc["tasacop"];
                    
                    
                    $sum_caja_registradora = $caja_inicial_suc["dejar_dolar"]+$this->dividir($caja_inicial_suc["dejar_peso"],$cop)+$this->dividir($caja_inicial_suc["dejar_bss"],$bs);
                    $sum_caja_chica = $caja_chica["dolarbalance"]+ $this->dividir($caja_chica["bsbalance"],$bs)+ $this->dividir($caja_chica["pesobalance"],$cop)+$caja_chica["eurobalance"];
                    $sum_caja_fuerte = $caja_fuerte["dolarbalance"]+ $this->dividir($caja_fuerte["bsbalance"],$bs)+ $this->dividir($caja_fuerte["pesobalance"],$cop)+$caja_fuerte["eurobalance"];
    
                    $sum_cajas = $sum_caja_registradora+$sum_caja_fuerte+$sum_caja_chica;
                    
                    $caja_inicial[$caja_inicial_suc["sucursal"]["codigo"]] = [
                        "caja_registradora" => [
                            "dolar" => $caja_inicial_suc["dejar_dolar"],
                            "peso" => $caja_inicial_suc["dejar_peso"],
                            "bs" => $caja_inicial_suc["dejar_bss"],
                            "euro" => 0,
                            "total_dolar" => $sum_caja_registradora,
                        ],
                        "caja_fuerte" => [
                            "dolar" => $caja_fuerte["dolarbalance"],
                            "bs" => $caja_fuerte["bsbalance"],
                            "peso" => $caja_fuerte["pesobalance"],
                            "euro" => $caja_fuerte["eurobalance"],
                            "total_dolar" => $sum_caja_fuerte,
                        ],
                        "caja_chica" => [
                            "dolar" => $caja_chica["dolarbalance"],
                            "bs" => $caja_chica["bsbalance"],
                            "peso" => $caja_chica["pesobalance"],
                            "euro" => $caja_chica["eurobalance"],
                            "total_dolar" => $sum_caja_chica,
                        ],
                        "sum_cajas" => $sum_cajas,
                    ];
                    $sum_caja_inicial += $sum_cajas;
                    $sum_caja_regis_inicial +=  $sum_caja_registradora;
                    $sum_caja_chica_inicial +=  $sum_caja_chica;
                    $sum_caja_fuerte_inicial +=  $sum_caja_fuerte;
                }
            }
            foreach ($blist as $banco) {
                $tasas = cierres::where("fecha","<=", $fechaBalanceGeneral)->orderBy("fecha","desc")->first();
                $bs = $tasas->tasa;
                $cop = $tasas->tasacop;

                $b = bancos::where("id_banco",$banco->id)->where("fecha","<",$fechaBalanceGeneral)->orderBy("fecha","desc")->first();

                /* $ban = (new BancosController)->bancosDataFun([
                    "qdescripcionbancosdata" => "",
                    "qbancobancosdata" => $banco->id,
                    "qfechabancosdata" => $fechaBalanceGeneral,
                    "fechaHastaSelectAuditoria" => $fechaBalanceGeneral,
                    "sucursalSelectAuditoria" => "",
                    "subviewAuditoria" => "cuadre",
                    "columnOrder" => "monto",
                    "order" => "desc",
                    "tipoSelectAuditoria" => "",
                    "showallSelectAuditoria" => false,
                    "ingegreSelectAuditoria" => "",
                ])["xfechaCuadre"];

                $positivo = count($ban)?$ban[0]["ingreso"]:0;
                $negativo = count($ban)?$ban[0]["egreso"]:0;
                
                
                $pun = abs($positivo)-abs($negativo); */


                $puntos = puntosybiopagos::where("fecha_liquidacion",$fechaBalanceGeneral)->where("id_banco",$banco->id)->where("tipo","LIKE","PUNTO")->sum("monto_liquidado");
                $saldo = $b?$b->saldo_real_manual +$puntos :0;
                $fecha = $b?$b->fecha:"";

                array_push($caja_inicial_banco, [
                    "ban" => $ban,
                    "positivo" => $positivo,
                    "negativo" => $negativo,
                    "realmanual" => $b?$b->saldo_real_manual:0,
                    "puntos_liquidados" => $puntos,
                    "fecha" => $fecha,
                    "banco"=> $banco->codigo,
                    "saldo" => $banco->moneda=="dolar"? 0: $saldo,
                    "saldo_dolar" => $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs),
                ]);
                $sum_caja_inicial_banco += $banco->moneda=="bs"? $saldo: 0;
                $sum_caja_inicial_banco_dolar += $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs);
            }
            $total_caja_inicial = $sum_caja_inicial+$sum_caja_inicial_banco_dolar;
        /// END CAJA INICIAL

        /// CAJA ACTUAL
            $fechaParaCajaActual = strtotime('+1 day', strtotime($fechaHastaBalanceGeneral));
            $fechaParaCajaActual = date('Y-m-d' , $fechaParaCajaActual);
            $caja_actual = [];
            $caja_actual_banco = [];
            $sum_caja_actual_banco = 0;
            $sum_caja_actual_banco_dolar = 0;
            $sum_caja_actual = 0;
            $sum_caja_regis_actual = 0;
            $sum_caja_chica_actual = 0;
            $sum_caja_fuerte_actual = 0;

            
            foreach ($sucursales as $key => $q) {
                $caja_actual_suc = cierres::with("sucursal")->where("id_sucursal",$q->id)->where("fecha","<",$fechaParaCajaActual)->orderBy("fecha","desc")->first();
                if ($caja_actual_suc) {
                    $caja_chica = cajas::where("id_sucursal",$q->id)->where("tipo",0)->where("fecha","<",$fechaParaCajaActual)->orderBy("fecha","desc")->orderBy("id","desc")->first();
                    $caja_fuerte = cajas::where("id_sucursal",$q->id)->where("tipo",1)->where("fecha","<",$fechaParaCajaActual)->orderBy("fecha","desc")->orderBy("id","desc")->first();
                    
                    $bs = $caja_actual_suc["tasa"];
                    $cop = $caja_actual_suc["tasacop"];
                    $sum_caja_registradora = $caja_actual_suc["dejar_dolar"]+$this->dividir($caja_actual_suc["dejar_peso"],$cop)+$this->dividir($caja_actual_suc["dejar_bss"],$bs);
                    $sum_caja_chica = $caja_chica["dolarbalance"]+ $this->dividir($caja_chica["bsbalance"],$bs)+ $this->dividir($caja_chica["pesobalance"],$cop)+$caja_chica["eurobalance"];
                    $sum_caja_fuerte = $caja_fuerte["dolarbalance"]+ $this->dividir($caja_fuerte["bsbalance"],$bs)+ $this->dividir($caja_fuerte["pesobalance"],$cop)+$caja_fuerte["eurobalance"];
    
                    $sum_cajas = $sum_caja_registradora+$sum_caja_fuerte+$sum_caja_chica;
                    
                    $caja_actual[$caja_actual_suc["sucursal"]["codigo"]] = [
                        "caja_registradora" => [
                            "dolar" => $caja_actual_suc["dejar_dolar"],
                            "peso" => $caja_actual_suc["dejar_peso"],
                            "bs" => $caja_actual_suc["dejar_bss"],
                            "euro" => 0,
                            "total_dolar" => $sum_caja_registradora,
                        ],
                        "caja_fuerte" => [
                            "dolar" => $caja_fuerte["dolarbalance"],
                            "bs" => $caja_fuerte["bsbalance"],
                            "peso" => $caja_fuerte["pesobalance"],
                            "euro" => $caja_fuerte["eurobalance"],
                            "total_dolar" => $sum_caja_fuerte,
                        ],
                        "caja_chica" => [
                            "dolar" => $caja_chica["dolarbalance"],
                            "bs" => $caja_chica["bsbalance"],
                            "peso" => $caja_chica["pesobalance"],
                            "euro" => $caja_chica["eurobalance"],
                            "total_dolar" => $sum_caja_chica,
                        ],
                        "sum_cajas" => $sum_cajas,
                    ];
                    $sum_caja_actual += $sum_cajas;
                    $sum_caja_regis_actual +=  $sum_caja_registradora;
                    $sum_caja_chica_actual +=  $sum_caja_chica;
                    $sum_caja_fuerte_actual +=  $sum_caja_fuerte;
                }
            }
            foreach ($blist as $banco) {
                $tasas = cierres::where("fecha","<", $fechaParaCajaActual)->orderBy("fecha","desc")->first();
                $bs = $tasas->tasa;
                $cop = $tasas->tasacop;

                $b = bancos::where("id_banco",$banco->id)->where("fecha","<",$fechaParaCajaActual)->orderBy("fecha","desc")->first();
                

                /* $ban = (new BancosController)->bancosDataFun([
                    "qdescripcionbancosdata" => "",
                    "qbancobancosdata" => $banco->id,
                    "qfechabancosdata" => $fechaBalanceGeneral,
                    "fechaHastaSelectAuditoria" => $fechaBalanceGeneral,
                    "sucursalSelectAuditoria" => "",
                    "subviewAuditoria" => "cuadre",
                    "columnOrder" => "monto",
                    "order" => "desc",
                    "tipoSelectAuditoria" => "",
                    "showallSelectAuditoria" => false,
                    "ingegreSelectAuditoria" => "",
                ])["xfechaCuadre"];

                $positivo = count($ban)?$ban[0]["ingreso"]:0;
                $negativo = count($ban)?$ban[0]["egreso"]:0;
                $pun = abs($positivo)-abs($negativo); */
                $puntos = puntosybiopagos::where("fecha_liquidacion",$fechaBalanceGeneral)->where("id_banco",$banco->id)->where("tipo","LIKE","PUNTO")->sum("monto_liquidado");


                $saldo = $b?$b->saldo_real_manual + $puntos :0;
                $fecha = $b?$b->fecha:"";
                
                array_push($caja_actual_banco, [
                    "fecha" => $fecha,
                    "banco"=> $banco->codigo,
                    "puntos_liquidados" => $puntos,
                    "saldo" => $banco->moneda=="dolar"? 0: $saldo,
                    "saldo_dolar" => $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs),
                ]);
                $sum_caja_actual_banco += $banco->moneda=="bs"? $saldo: 0;
                $sum_caja_actual_banco_dolar += $banco->moneda=="dolar"?$saldo:$this->dividir($saldo,$bs);
            }

            $matriz = cajas::where("id_sucursal",13)->where("fecha","<",$fechaParaCajaActual)->orderBy("fecha","desc")->first();

            $sum_caja_actual += $matriz?$matriz->dolarbalance:0;
            $total_caja_actual = $sum_caja_actual+$sum_caja_actual_banco_dolar;
        /// END CAJA ACTUAL



        $debetener =  ($total_caja_inicial + $total) - $pagoProveedorBruto - abs($gastosfijosSum) - abs($gastosvariablesSum) - $sumFDI ;
        $bsactual = $this->getTasa()["bs"];
        $cuadre = $debetener-$total_caja_actual;

        $inicial_inventariobase = 0;
        $inicial_inventarioventa = 0;
        $final_inventariobase = 0;
        $final_inventarioventa = 0;
        $aumento_inventariobase = 0;
        $aumento_inventarioventa = 0;

        $cxc_inicial = 0;
        $cxc_final = 0;
        $cxc_aumento = 0;

        $cxp_inicial = 0;
        $cxp_final = 0;
        $cxp_aumento = 0;



        foreach ($sucursales as $i => $e) {
            $inicial = cierres::where("id_sucursal",$e->id)->where("fecha","<=",$fechaBalanceGeneral)->orderBy("fecha","desc")->first();
            $final = cierres::where("id_sucursal",$e->id)->where("fecha","<=",$fechaHastaBalanceGeneral)->orderBy("fecha","desc")->first();
            $inicial_inventariobase += $inicial->inventariobase;
            $inicial_inventarioventa += $inicial->inventarioventa;
            $final_inventariobase += $final->inventariobase;
            $final_inventarioventa += $final->inventarioventa;

            $cxc_inicial += $inicial->creditoporcobrartotal;
            $cxc_final += $final->creditoporcobrartotal;

        }

        $diffbase = $final_inventariobase-$inicial_inventariobase;
        $diffventa = $final_inventarioventa-$inicial_inventarioventa;
        $aumento_inventariobase = ($diffbase*100)/$inicial_inventariobase;
        $aumento_inventarioventa = ($diffventa*100)/$inicial_inventarioventa;

        $diffcxp = $cxc_final-$cxc_inicial;
        $cxc_aumento = ($diffcxp*100)/$cxc_inicial;



        return [
            "inicial_inventariobase" => $inicial_inventariobase,
            "inicial_inventarioventa" => $inicial_inventarioventa,

            "final_inventariobase" => $final_inventariobase,
            "final_inventarioventa" => $final_inventarioventa,
            "aumento_inventariobase" => $aumento_inventariobase,
            "aumento_inventarioventa" => $aumento_inventarioventa,

            "cxc_inicial" => $cxc_inicial,
            "cxc_final" => $cxc_final,
            "cxc_aumento" => $cxc_aumento,
           /*  "PRUEBA_pagoProveedorBruto" => $pagoProveedorBruto,
            "PRUEBA_gastosfijosSum" => $gastosfijosSum,
            "PRUEBA_gastosvariablesSum" => $gastosvariablesSum,
            "PRUEBA_sumFDI" => $sumFDI, */
            "total_caja_inicial" => $total_caja_inicial,
            "total" => $total,
            "sumEgresos" => abs($pagoProveedorBruto) + abs($gastosfijosSum) + abs($gastosvariablesSum) + abs($sumFDI),
            
            "debetener" => $debetener,
            "caja_inicial" => $total_caja_inicial,
            "gastos"=>$gastos,

            "sumArrcat" =>$sumArrcat,
            "sumArrcatgeneral" =>$sumArrcatgeneral,
            "sumArringresoegreso" =>$sumArringresoegreso,
            "sumArrvariablefijo" =>$sumArrvariablefijo,
            "pagoproveedor" => $pagoproveedor,

            "gastosfijosSum"=>$gastosfijosSum,
            "gastosvariablesSum"=>$gastosvariablesSum,
            "fdi" => $sumFDI,
            

            "gananciaNeta" => $gananciaNeta,
            "sumGastos"=>$sumGastos,

            "porcevbruta" => [
                "labels"=>["VENTA BRUTA","GASTOS"],
                "series"=>[($total-$sumGastos),$sumGastos],
            ],
            "porcevbrutanum" => $porcevbrutanum,

            "porcegbruta" => [
                "labels"=>["GANANCIA NETA","GASTOS"],
                "series"=>[($ganancia-$sumGastos),$sumGastos],
            ],
            "porcegbrutanum" => $porcegbrutanum,

            "porcegneta" => [
                "labels"=>["GANANCIA NETA","GASTOS"],
                "series"=>[($gananciaNeta-$sumGastos),$sumGastos],
            ],
            "porcegnetanum" => $porcegnetanum,


            "efectivodolar" =>$dolarbalance,
            "efectivoData" =>$efectivoData,
            "banco" =>$banco,
            "bancoData" =>$bancoData,
            "inventario" =>$inventario,

            "debito" => $debito,
            "efectivo" => $efectivo,
            "transferencia" => $transferencia,
            "biopago" => $biopago,
            "ganancia" =>$ganancia,

            "cierresData" =>$cierreData,
            "cxc" =>$cxc,
            "cxcData" =>$cxcData,
            "cxp" =>$cxp,
            "cxpData" =>$cxpData,
            "sumPagoProveedorEfectivo" => $sumPagoProveedorEfectivo,
            "sumPagoProveedorBanco" => $sumPagoProveedorBanco,
            "sumPagoProveedorBancoReal" => $sumPagoProveedorBancoReal, 
            "perdidaPagoProveedor" => $perdidaPagoProveedor,
            "sumPagoProveedorBancoEfectivoReal" => $pagoProveedorBruto, 
            
            "sum_caja_inicial" => $sum_caja_inicial,
            "sum_caja_inicial_banco_dolar" => $sum_caja_inicial_banco_dolar,
            "caja_inicial_banco" => $caja_inicial_banco,
            
            "total_caja_actual" => $total_caja_actual,
            "caja_actual_banco" => $caja_actual_banco,
            "sum_caja_actual" => $sum_caja_actual,
            "sum_caja_actual_banco" => $sum_caja_actual_banco,
            "sum_caja_actual_banco_dolar" => $sum_caja_actual_banco_dolar,
            
            "tengo" => $total_caja_actual,
            "cuadre" => $cuadre,
        ];
    }
    function getControldeefectivo($fechasMain1, $fechasMain2, $sucursalBalanceGeneral, $filtros)
    {
        $controlefecQ = $filtros["controlefecQDescripcion"];
        $controlefecQCategoria = $filtros["controlefecSelectCat"];

        $controlefecSelectGeneral = $filtros["controlefecSelectGeneral"];

        $cajas = cajas::with(["cat", "sucursal"])->where("tipo", $controlefecSelectGeneral)
        ->when($controlefecQCategoria, function ($q) use ($controlefecQCategoria) {
            $q->where("categoria", $controlefecQCategoria);
        })
        ->when($sucursalBalanceGeneral, function ($q) use ($sucursalBalanceGeneral) {
            $q->where("id_sucursal", $sucursalBalanceGeneral);
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
