<?php

namespace App\Http\Controllers;

use App\Models\cierres;
use App\Models\sucursal;
use Illuminate\Http\Request;


class CierresController extends Controller
{
   public function setCierreFromSucursalToCentral(Request $req)
   {
        try {
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_origen = $id_ruta["id_origen"];
            
            $cierre = $req->cierre;
            
            
            $cierresobj = cierres::updateOrCreate([
                "fecha" => $cierre["fecha"],
            ],[
                "id_sucursal" => $id_origen,
                
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
                "id_usuario" => $cierre["id_usuario"],
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
            ]);
            
            if ($cierresobj->save()) {
                return "Éxito al registrar cierre en Central";
            }        
        } catch (\Exception $e) {
            return "Error: ".$e->getMessage();
        }
   }
    public function getCierres($fechasMain1,$fechasMain2,$filtros)
    {
        return sucursal::all()->map(function($q) use ($fechasMain1,$fechasMain2){
            $cierre = cierres::where("id_sucursal",$q->id)
            ->whereBetween("fecha",[$fechasMain1,$fechasMain2])
            ->orderBy("fecha","desc")
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
                return $this->getCierres($fechasMain1,$fechasMain2,$filtros);
                break;
                case 'inventario':
                    
                    break;
                case 'gastos':
                    return (new GastosController)->getGastos($fechasMain1,$fechasMain2,$filtros);
            
                break;
        }
    }
    public function getCierreSucursal($fechasMain1,$fechasMain2,$id_sucursal,$filtros)
    {
        $array = cierres::where("id_sucursal", $id_sucursal)
        ->whereBetween("fecha",[$fechasMain1,$fechasMain2])
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
        
        
        $array = $array->map(function($q){
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


        $viewmainPanel = $req->viewmainPanel;

        switch ($viewmainPanel) {
            case 'panel':
            
                break;
            case 'cierres':
                return $this->getCierreSucursal($fechasMain1,$fechasMain2,$id_sucursal,$filtros);
                break;
            case 'inventario':
            
                break;
            case 'gastos':
            
                break;
        }
    }
}
