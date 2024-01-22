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
        puntosybiopagos::where("id_sucursal",$id_sucursal)->where("created_at","LIKE",$today."%")->delete();
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
                    $loteSql = puntosybiopagos::updateOrCreate([
                        "fecha" => $lote["fecha"],
                        "id_usuario" => $lote["id_usuario"],
                        "id_sucursal" => $id_origen,
                        "tipo" => $lote["tipo"],
                    ], [
                        "loteserial" => $lote["lote"],
                        "monto" => $lote["monto"],
                        "banco" => $lote["banco"],
                    ]);

                    if ($loteSql) {
                        $numlote++;
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
    public function getsucursalDetallesData(Request $req)
    {
        $id_sucursal = $req->sucursalSelect;
        $fechasMain1 = $req->fechasMain1;
        $fechasMain2 = $req->fechasMain2;
        $filtros = $req->filtros;

        $tipo_usuario = session("tipo_usuario");


        $subviewpanelsucursales = $req->subviewpanelsucursales;

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
                if ($tipo_usuario==1) {
                    
                    return $this->getCierreSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'controldeefectivo':
                if ($tipo_usuario==1) {
                    
                    return $this->getControldeefectivo($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'nomina':
                if ($tipo_usuario==1) {
                    
                    return $this->getNominasSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'comovamos':
                if ($tipo_usuario==1) {
                    
                    return $this->comovamos($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
            }case
             'fallas':
                if ($tipo_usuario==1) {
                    
                    return $this->getFallas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;
            case 'creditos':
                if ($tipo_usuario==1) {
                    
                    return $this->getCreditos($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
                 break;

            case 'aprobacioncajafuerte':
            if ($tipo_usuario==1) {
                
                return (new CajasAprobacionController)->getAprobacionCajas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
            }
                break;

            case 'cuentasporpagar':
                if ($tipo_usuario==1) {
                    
                    return (new CuentasporpagarController)->getCuentas($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;

            case 'cuentasporpagardetalles':
                if ($tipo_usuario==1) {
                    
                    return (new CuentasporpagarController)->selectCuentaPorPagarProveedorDetalles($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                }
            break;

                    


        }
    }

    function getCreditos($fechasMain1, $fechasMain2, $id_sucursal, $filtros) {
        $data = creditos::with(["sucursal","cliente"]) 
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })->get();

        return [
            "data" => $data,
            "num" => 0
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
                $q->ticked = number_format($q->total/$q->numventas,2);
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

    function getControldeefectivo($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        $controlefecQ = "";
        $controlefecQCategoria = "";

        $controlefecSelectGeneral = $filtros["controlefecSelectGeneral"];

        $cajas = cajas::with(["cat", "sucursal"])->where("tipo", $controlefecSelectGeneral)
        ->when($controlefecQ, function ($q) use ($controlefecQ) {
            $q->orWhere("concepto", $controlefecQ);
            $q->orWhere("monto", $controlefecQ);
        })
        ->when($controlefecQCategoria, function ($q) use ($controlefecQCategoria) {
            $q->where("categoria", $controlefecQCategoria);
        })
        ->when($id_sucursal, function ($q) use ($id_sucursal) {
            $q->where("id_sucursal", $id_sucursal);
        })
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])->orderBy("idinsucursal", "desc")
        ->get();



        

        $categorias = [];
        $catGeneral = [];
      

        $cajas->map(function($q) use (&$categorias,&$catGeneral){

            if ($q["cat"]) {
                
                if (isset($categorias[$q["cat"]["indice"]])) {
                    $categorias[$q["cat"]["indice"]] = [
                        "categoria" => $q["categoria"],
                        "nombre" => $q["cat"]["nombre"],
                        "montodolar" => $categorias[$q["cat"]["indice"]]["montodolar"] + ($q["montodolar"]),
                        "montobs" => $categorias[$q["cat"]["indice"]]["montobs"] + ($q["montobs"]),
                        "montopeso" => $categorias[$q["cat"]["indice"]]["montopeso"] + ($q["montopeso"]),
                        "montoeuro" => $categorias[$q["cat"]["indice"]]["montoeuro"] + ($q["montoeuro"]),
                    ];
                }else{
                    $categorias[$q["cat"]["indice"]] = [
                        "categoria" => $q["categoria"],
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
                        "nombre" => $q["cat"]["catgeneral"],
                        "montodolar" => $catGeneral[$q["cat"]["catgeneral"]]["montodolar"] + ($q["montodolar"]),
                        "montobs" => $catGeneral[$q["cat"]["catgeneral"]]["montobs"] + ($q["montobs"]),
                        "montopeso" => $catGeneral[$q["cat"]["catgeneral"]]["montopeso"] + ($q["montopeso"]),
                        "montoeuro" => $catGeneral[$q["cat"]["catgeneral"]]["montoeuro"] + ($q["montoeuro"]),
                    ];
                }else{
                    $catGeneral[$q["cat"]["catgeneral"]] = [
                        "categoria" => $q["categoria"],
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

        return nomina::with("sucursal")
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
            ->orderBy("nominacargo","desc")
            ->get();
    }
}
