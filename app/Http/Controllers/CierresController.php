<?php

namespace App\Http\Controllers;

use App\Models\cierres;
use App\Models\cajas;
use App\Models\comovamos;
use App\Models\inventario_sucursal;
use App\Models\nomina;
use App\Models\puntosybiopagos;
use App\Models\sucursal;
use Illuminate\Http\Request;


class CierresController extends Controller
{
    public function setCierreFromSucursalToCentral(Request $req)
    {
        try {
            $codigo_origen = $req->codigo_origen;

            $lotes = $req->lotes;
            $biopagos = $req->biopagos;


            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
            $id_origen = $id_ruta["id_origen"];

            $cierre = $req->cierre;

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

            foreach ($lotes as $key => $e) {

                puntosybiopagos::updateOrCreate([
                    "fecha" => $e["fecha"],
                    "id_usuario" => $e["id_usuario"],
                    "id_sucursal" => $id_origen,
                    "tipo" => $e["tipo"],
                ], [
                    "monto" => $e["monto"],
                    "loteserial" => $e["lote"],
                    "banco" => $e["banco"],

                    "fecha" => $e["fecha"],
                    "id_sucursal" => $id_origen,
                    "id_usuario" => $e["id_usuario"],
                    "tipo" => $e["tipo"],


                ]);
            }



            foreach ($biopagos as $key => $value) {
                puntosybiopagos::updateOrCreate([
                    "fecha" => $e["fecha"],
                    "id_usuario" => $e["id_usuario"],
                    "id_sucursal" => $id_origen,
                    "tipo" => $e["tipo"],
                ], [
                    "monto" => $e["monto"],
                    "loteserial" => $e["serial"],
                    "banco" => "BDV",
                    "fecha" => $e["fecha"],
                    "id_sucursal" => $id_origen,
                    "id_usuario" => $e["id_usuario"],
                    "tipo" => $e["tipo"],
                ]);
            }






            if ($cierresobj->save()) {
                return "Exito al registrar Cierre en Central";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
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
            ->get();

        $sumdebito = $array->sum("debito");
        $sumefectivo = $array->sum("efectivo");
        $sumtransferencia = $array->sum("transferencia");

        $sum = [
            "numventas" => $array->sum("numventas"),
            "numero" => $array->count(),
            "total" => moneda($sumdebito + $sumefectivo + $sumtransferencia),
            "debito" => moneda($sumdebito),
            "efectivo" => moneda($sumefectivo),
            "transferencia" => moneda($sumtransferencia),
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
        ];


        $array = $array->map(function ($q) {
            $q->total = moneda($q->debito + $q->efectivo + $q->transferencia);

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


        $subviewpanelsucursales = $req->subviewpanelsucursales;

        switch ($subviewpanelsucursales) {
            case 'panel':

                break;
            case 'cierres':
                return $this->getCierreSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);
                break;
            case 'inventario':
                return $this->getInvSucursal($id_sucursal, $filtros);

                break;
            case 'puntosyseriales':
                return $this->getPuntosyseriales($fechasMain1, $fechasMain2, $id_sucursal, $filtros);

                break;
            case 'controldeefectivo':
                return $this->getControldeefectivo($fechasMain1, $fechasMain2, $id_sucursal, $filtros);

                break;

            case 'nomina':
                return $this->getNominasSucursal($fechasMain1, $fechasMain2, $id_sucursal, $filtros);

                break;
            case 'comovamos':
                return $this->comovamos($fechasMain1, $fechasMain2, $id_sucursal, $filtros);

                break;


        }
    }
    function comovamos($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {
        $c = comovamos::with("sucursal")
            ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
            ->orderBy("total", "desc")->get();

        return [
            "comovamos" => $c,
            "sum" =>[
                "transferencia" => $c->sum("transferencia"),
                "biopago" => $c->sum("biopago"),
                "debito" => $c->sum("debito"),
                "efectivo" => $c->sum("efectivo"),
                "numventas" => $c->sum("numventas"),
                "total" => $c->sum("total"),
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

        $cajas = cajas::with(["cat", "sucursal", "responsable", "asignar"])->where("tipo", $controlefecSelectGeneral)
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
        ->whereBetween("fecha", [$fechasMain1, $fechasMain2])->orderBy("idinsucursal", "desc")->get();


        $categorias = [];
        $responsable = [];
        $asignar = [];

        $cajas->map(function($q) use (&$categorias,&$responsable,&$asignar){

            if ($q["cat"]) {
                
                if (isset($categorias[$q["cat"]["indice"]])) {
                    $categorias[$q["cat"]["indice"]] = [
                        "nombre" => $q["cat"]["nombre"],
                        "montodolar" => $categorias[$q["cat"]["indice"]]["montodolar"] + ($q["montodolar"]),
                        "montobs" => $categorias[$q["cat"]["indice"]]["montobs"] + ($q["montobs"]),
                        "montopeso" => $categorias[$q["cat"]["indice"]]["montopeso"] + ($q["montopeso"]),
                        "montoeuro" => $categorias[$q["cat"]["indice"]]["montoeuro"] + ($q["montoeuro"]),
                    ];
                }else{
                    $categorias[$q["cat"]["indice"]] = [
                        "nombre" => $q["cat"]["nombre"],
                        "montodolar" => $q["montodolar"],
                        "montobs" => $q["montobs"],
                        "montopeso" => $q["montopeso"],
                        "montoeuro" => $q["montoeuro"],
                    ];
                }
            }

            if ($q["responsable"]) {
                if (isset($responsable[$q["responsable"]["indice"]])) {
                    $responsable[$q["responsable"]["indice"]] = [
                        "nombre" => $q["responsable"]["nombre"],

                        "montodolar" => $responsable[$q["responsable"]["indice"]]["montodolar"] + $q["montodolar"],
                        "montobs" => $responsable[$q["responsable"]["indice"]]["montobs"] + $q["montobs"],
                        "montopeso" => $responsable[$q["responsable"]["indice"]]["montopeso"] + $q["montopeso"],
                        "montoeuro" => $responsable[$q["responsable"]["indice"]]["montoeuro"] + $q["montoeuro"],
                    ];
                }else{
                    $responsable[$q["responsable"]["indice"]] = [
                        "nombre" => $q["responsable"]["nombre"],

                        "montodolar" => $q["montodolar"],
                        "montobs" => $q["montobs"],
                        "montopeso" => $q["montopeso"],
                        "montoeuro" => $q["montoeuro"],
                    ];
                }
            }

            if ($q["asignar"]) {
                if (isset($asignar[$q["asignar"]["indice"]])) {
                    $asignar[$q["asignar"]["indice"]] = [
                        "nombre" => $q["asignar"]["nombre"],

                        "montodolar" => $asignar[$q["asignar"]["indice"]]["montodolar"] + $q["montodolar"],
                        "montobs" => $asignar[$q["asignar"]["indice"]]["montobs"] + $q["montobs"],
                        "montopeso" => $asignar[$q["asignar"]["indice"]]["montopeso"] + $q["montopeso"],
                        "montoeuro" => $asignar[$q["asignar"]["indice"]]["montoeuro"] + $q["montoeuro"],
                    ];
                }else{
                    $asignar[$q["asignar"]["indice"]] = [
                        "nombre" => $q["asignar"]["nombre"],

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
                "responsable" => $responsable,
                "asignar" => $asignar,
            ]
        ];
    }


    function getPuntosyseriales($fechasMain1, $fechasMain2, $id_sucursal, $filtros)
    {

        return puntosybiopagos::with("sucursal")
            ->whereBetween("fecha", [$fechasMain1, $fechasMain2])
            ->when($id_sucursal, function ($q) use ($id_sucursal) {
                $q->where("id_sucursal", $id_sucursal);
            })
            ->get();
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
                    ->orwhere("nominanombre", $filtronominaq)
                    ->orwhere("nominacedula", $filtronominaq)
                    ->orwhere("nominatelefono", $filtronominaq);
            })
            ->get();
    }
}
