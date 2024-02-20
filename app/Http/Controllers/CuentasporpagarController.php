<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar;
use App\Http\Requests\StorecuentasporpagarRequest;
use App\Http\Requests\UpdatecuentasporpagarRequest;
use App\Models\cuentasporpagar_pagos;
use App\Models\proveedores;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Response;


class CuentasporpagarController extends Controller
{

    function getBalance($id_proveedor,$cuentaporpagarAprobado){
        $b = cuentasporpagar::selectRaw("@monto_sindescuento := SUM((1-(descuento/100))*monto) AS monto_sindescuento")
        ->where("id_proveedor", $id_proveedor)
        ->where("aprobado",$cuentaporpagarAprobado)
        ->first("monto_sindescuento");
        if ($b) {
            return $b->monto_sindescuento;
        }
        return 0;
    }
    function sendPagoCuentaPorPagar(Request $req) {
        $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
        $cuentasPagosMonto = $req->cuentasPagosMonto;
        $cuentasPagosMetodo = $req->cuentasPagosMetodo;
        $cuentasPagosFecha = $req->cuentasPagosFecha;
        $id_pro = $req->id_pro;
        $selectAbonoFact = $req->selectAbonoFact;

        $id = isset($req->id)? $req->id: null;
        $id_sucursal = isset($req->id_sucursal)? $req->id_sucursal: null;

        if (
            !$cuentasPagosDescripcion || 
            !$cuentasPagosMonto || 
            !$cuentasPagosMetodo || 
            !$cuentasPagosFecha 
        ) {
            return [
                "estado" => false,
                "msj" => "Error: Campos vacíos",
                "id_proveedor" => $id_pro
            ];
        }
        
        

        $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
            "nombre" => "ADMINISTRACION",
            "codigo" => "administracion",
        ]);

        if ($su) {

            $pago = $this->setPago([
                "id_sucursal" => $id_sucursal? $id_sucursal: $su->id,
                "idinsucursal_pago" => $id?$id:time(),

                "id_proveedor_caja" => $id_pro,
                "numfact_desc" => $cuentasPagosDescripcion,
                "monto" => $cuentasPagosMonto,
                "fecha_creada" => $cuentasPagosFecha,
                "metodo" => $cuentasPagosMetodo,
                "selectAbonoFact" =>$selectAbonoFact,
                "aprobado" =>1,
            ]);
            if ($pago) {
                return [
                    "estado" => true,
                    "msj" => "Pago registrado con éxito",
                    "id_proveedor" => $id_pro
                ];
            }
        }
    }
    function setCuentaPorPagar($arr,$search) {

        return cuentasporpagar::updateOrCreate($search,$arr);
    }
    function setPago($arr) {

        $id_sucursal = $arr["id_sucursal"];
        $idinsucursal_pago = $arr["idinsucursal_pago"];
        $id_proveedor_caja = $arr["id_proveedor_caja"];
        $numfact_desc = $arr["numfact_desc"];
        $monto = $arr["monto"];
        $fecha_creada = $arr["fecha_creada"];
        $metodo = isset($arr["metodo"])?$arr["metodo"]:null;
        $aprobado = isset($arr["aprobado"])?$arr["aprobado"]:0;
        
        
        $selectAbonoFact = isset($arr["selectAbonoFact"])?$arr["selectAbonoFact"]:null;
        
        $arrinsert = [
            "id_proveedor" => $id_proveedor_caja,
            "tipo" => 1, //COMPRAS
            "frecuencia" => 0,
            "id_sucursal" => $id_sucursal,

            "idinsucursal" => $idinsucursal_pago,
            "numfact" => $numfact_desc,
            "numnota" => "",
            "descripcion" => $numfact_desc,

            "subtotal" => $monto,
            "monto" => $monto,
            "fechaemision" => $fecha_creada,
            "fechavencimiento" => $fecha_creada,
            "fecharecepcion" => $fecha_creada,
            "nota" => "",
            "metodo" => $metodo,
            "aprobado" => $aprobado,
        ];
        $search = [
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $idinsucursal_pago
        ];
        
        $cuenta = $this->setCuentaPorPagar($arrinsert,$search);        
        
        if ($cuenta) {
            if ($selectAbonoFact) {
                if (count($selectAbonoFact)) {
                    $msjAbono = "";
                    cuentasporpagar_pagos::where("id_pago",$cuenta->id)->delete();
                    foreach ($selectAbonoFact as $e) {
                        $update_cuenta = cuentasporpagar_pagos::updateOrCreate([
                            "id_factura" => $e["id"],
                            "id_pago" => $cuenta->id,
                        ],[
                            "id_factura" => $e["id"],
                            "id_pago" => $cuenta->id,
                            "monto" => $e["val"],
                        ]);
                        $updateUpdated_at = cuentasporpagar::find($e["id"]);
                        $updateUpdated_at->updated_at = date("Y-m-d H:i:s");
                        $updateUpdated_at->save();
                        
                        if ($update_cuenta) {
                            $msjAbono .= $e["val"]." | ";
                        }else{
                            $msjAbono .= "ERROR: " . $e["val"]." | ";
                        }
                        
                    }
                    return $msjAbono;
                    
                }
            }
        }
    }
    function sendFacturaCentral(Request $req){
        try {
            
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];


            $factura = json_decode($req->factura,2);
            $imagen = $req->imagen;

            

            if ($factura["proveedor"]) {
                $id_proveedor = proveedores::where("rif",$factura["proveedor"]["rif"])->get()->first();

                
                if ($id_proveedor) {
                    $factor = -1;
                    $arrinsert = [
                        "id_proveedor" => $id_proveedor->id,
                        "tipo" => 1, //COMPRAS
                        "frecuencia" => 0,
                        "id_sucursal" => $id_sucursal,

                        "idinsucursal" => $factura["id"],
                        "numfact" => $factura["numfact"],
                        "numnota" => $factura["numnota"],
                        "descripcion" => $factura["descripcion"],

                        "subtotal" => $factura["subtotal"]*$factor,
                        "descuento" => $factura["descuento"]*$factor,
                        "monto_exento" => $factura["monto_exento"]*$factor,
                        "monto_gravable" => $factura["monto_gravable"]*$factor,
                        "iva" => $factura["iva"]*$factor,
                        "monto" => $factura["monto"]*$factor,
                        
                        "fechaemision" => $factura["fechaemision"],
                        "fechavencimiento" => $factura["fechavencimiento"],
                        "fecharecepcion" => $factura["fecharecepcion"],
                        "nota" => $factura["nota"],
                    ];

                    $search = [
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $factura["id"]
                    ];
                    $registrarfactura = $this->setCuentaPorPagar($arrinsert,$search);
        
                    if ($registrarfactura) {
                        $filename = $registrarfactura->id . "." . $imagen->getClientOriginalExtension();
                        $imagen->move(public_path('facturas'), $filename);
                        $updateImage = cuentasporpagar::find($registrarfactura->id);
                        $updateImage->descripcion = $filename;
                        $updateImage->save();

                        return [
                            "msj" => "Desde Central: Éxito al registrar Factura",
                            "idinsucursal" => $factura["id"]
                        ];
                    }
        
                }
            }else{
                return "Error CuentasporpagarController sendFacturaCentral";
            }

        } catch (\Exception $e) {
            return $e->getMessage()." LINEA ".$e->getLine()." CuentasporpagarController sendFacturaCentral";
        }


    }
    function showImageFact() {
        
    }

    function getCuentas($fechasMain1, $fechasMain2, $id_sucursal, $filtros){
        $qcuentasPorPagar = $filtros["qcuentasPorPagar"];
        $totalSum = 0;

        $cuentasporpagar = proveedores::when($qcuentasPorPagar!="",function($q) use ($qcuentasPorPagar){
            $q->orWhere("descripcion","LIKE","%$qcuentasPorPagar%")
            ->orWhere("rif","LIKE","%$qcuentasPorPagar%");
        })
        ->get()
        ->map(function($q) use (&$totalSum){
            $b = $this->getBalance($q->id,1);
            $q->balance = $b; 

            $totalSum += $b;
            return $q; 
        })->toArray();

        $cuentasporpagarColumn = array_column($cuentasporpagar, 'balance');
        array_multisort($cuentasporpagarColumn, SORT_ASC, $cuentasporpagar);
        
        return [
            "cuentasporpagar" => $cuentasporpagar,
            "sum" => $totalSum,
        ];
    }

    function saveNewFact(Request $req) {

        try {
            $newfactid_proveedor = $req->newfactid_proveedor;
            $newfactnumfact = $req->newfactnumfact;
            $newfactnumnota = $req->newfactnumnota;
            $newfactdescripcion = $req->newfactdescripcion;
            $newfactsubtotal = $req->newfactsubtotal;
            $newfactdescuento = $req->newfactdescuento;
            $newfactmonto_exento = $req->newfactmonto_exento;
            $newfactmonto_gravable = $req->newfactmonto_gravable;
            $newfactiva = $req->newfactiva;
            $newfactmonto = $req->newfactmonto;
            $newfactfechaemision = $req->newfactfechaemision;
            $newfactfechavencimiento = $req->newfactfechavencimiento;
            $newfactfecharecepcion = $req->newfactfecharecepcion;
            $newfactnota = $req->newfactnota;
            $newfacttipo = $req->newfacttipo;
            $newfactfrecuencia = $req->newfactfrecuencia;
    
            $id = $req->id;

            $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
                "nombre" => "ADMINISTRACION",
                "codigo" => "administracion",
            ]);

            if ($su) {
                $factor = -1;
                if ($id) {
                    $checkabonocred = cuentasporpagar::find($id);
                    if ($checkabonocred) {
                        if ($checkabonocred->monto<0) {
                            $factor = -1;
                        }
                    }
                }
                $arrinsert = [
                    "numfact" => $newfactnumfact,
                    "numnota" => $newfactnumnota,
                    "descripcion" => $newfactdescripcion,
                    
                    "subtotal" => $newfactsubtotal*$factor,
                    "descuento" => $newfactdescuento,
                    "monto_exento" => $newfactmonto_exento*$factor,
                    "monto_gravable" => $newfactmonto_gravable*$factor,
                    "iva" => $newfactiva*$factor,
                    "monto" => $newfactmonto*$factor,
                    
                    "nota" => $newfactnota,
                    "tipo" => $newfacttipo,
                    "frecuencia" => $newfactfrecuencia,
                ];
                
                if (!$id) {
                    $arrinsert["id_proveedor"] = $newfactid_proveedor;
                    $arrinsert["id_sucursal"] = $su->id;
                    $arrinsert["fechaemision"] = $newfactfechaemision;
                    $arrinsert["fechavencimiento"] = $newfactfechavencimiento;
                    $arrinsert["fecharecepcion"] = $newfactfecharecepcion;
                }
                $search = ["id" => $id];
                $cu = $this->setCuentaPorPagar($arrinsert,$search);
            }
            
            if ($cu) {
                return ["estado" => true, "msj"=>"Éxito al registar"];
            }
        } catch (\Exception $e) {
            return [
                "estado" => false,
                "msj" => $e->getMessage()
            ];
        }
        
    }
    function changeAprobarFact(Request $req) {
        $c = cuentasporpagar::find($req->id);
        if ($c->aprobado == 1) {
            $c->aprobado = 0;
        }else{
            $c->aprobado = 1;
        }
        $c->save();
    }
    function delCuentaPorPagar(Request $req) {
        try {
            $id = $req->id;
     
            $cuenta = cuentasporpagar::find($id);
            if ($cuenta->aprobado==1) {
                 return [
                     "estado"=> false,
                     "msj"=> "Factura aprobada. No se puede eliminar",
                 ];
            }else{
                 if ($cuenta->delete()) {
                     return [
                         "estado"=> true,
                         "msj"=> "Éxito al eliminar",
                     ];
                 }
            }
        } catch (\Exception $e) {
            return $e->getMessage()." LINEA ".$e->getLine()." CuentasporpagarController delCuentaPorPagar";

        }
    }

    function sendDescuentoGeneralFats(Request $req) {
        $dataselectFacts = $req->dataselectFacts;
        $descuentoGeneralFats = $req->descuentoGeneralFats;

        foreach ($dataselectFacts as $key => $e) {
            $c = cuentasporpagar::find($e["id"]);
            $c->descuento = $descuentoGeneralFats;
            $c->save();
        }
    }
    function selectCuentaPorPagarProveedorDetalles(Request $req) {
        
        $id_proveedor = $req->id_proveedor=="null"?null:$req->id_proveedor;
        $cuentaporpagarAprobado = $req->cuentaporpagarAprobado;
        $categoriacuentasPorPagarDetalles = $req->categoriacuentasPorPagarDetalles;
        $tipocuentasPorPagarDetalles = $req->tipocuentasPorPagarDetalles;
        $qcuentasPorPagarTipoFact = $req->qcuentasPorPagarTipoFact;
        $qCampocuentasPorPagarDetalles = $req->qCampocuentasPorPagarDetalles;
        $qcuentasPorPagarDetalles = $req->qcuentasPorPagarDetalles;
        $OrdercuentasPorPagarDetalles = $req->OrdercuentasPorPagarDetalles;
        $sucursalcuentasPorPagarDetalles = $req->sucursalcuentasPorPagarDetalles;
        $type = $req->type;
        
        
        
        $todayWithoutDateTime = (new NominaController)->today();
        $today = new \DateTime($todayWithoutDateTime);
        $detalles = cuentasporpagar::with(["sucursal","proveedor","pagos"=>function($q) {
            $q->orderBy("id","desc");
        },"facturas"])
        ->selectRaw("*, @monto_abonado := ( SELECT sum(`cuentasporpagar_pagos`.`monto`) FROM cuentasporpagar_pagos WHERE `cuentasporpagar_pagos`.`id_factura` =`cuentasporpagars`.`id` ) as monto_abonado")
        ->where("aprobado",$cuentaporpagarAprobado)

        ->when( ($id_proveedor != "" && $id_proveedor != null),function($q) use ($id_proveedor){
            $q->where("id_proveedor",$id_proveedor);
        }) 
        ->when($sucursalcuentasPorPagarDetalles!="",function($q) use ($sucursalcuentasPorPagarDetalles){
            $q->where("id_sucursal",$sucursalcuentasPorPagarDetalles);
        })
        ->when($qcuentasPorPagarDetalles!="", function($q) use($qcuentasPorPagarDetalles, $sucursalcuentasPorPagarDetalles) {
            $q->where(function($q) use ($sucursalcuentasPorPagarDetalles,$qcuentasPorPagarDetalles) {
                $q->orWhere("numfact","LIKE","%$qcuentasPorPagarDetalles%")
                ->orWhere("monto","LIKE","$qcuentasPorPagarDetalles%")
                ->when($sucursalcuentasPorPagarDetalles=="",function($qq) use ($qcuentasPorPagarDetalles) {
                    $qq->orWhereIn("id_sucursal",sucursal::where("nombre","LIKE","$qcuentasPorPagarDetalles%")->select("id"));
                });
            });
            
        })

        ->when($categoriacuentasPorPagarDetalles!="",function($q) use ($categoriacuentasPorPagarDetalles) {
            $q->where("tipo","$categoriacuentasPorPagarDetalles");
        })
        ->when($tipocuentasPorPagarDetalles!="",function($q) use ($tipocuentasPorPagarDetalles) {

            if ($tipocuentasPorPagarDetalles=="DEUDA") {
                $q->where("monto","<",0);
            }else{
                $q->where("monto",">",0);
            }
            
        })
        ->when($qcuentasPorPagarTipoFact=="",function($q) {
            $q->where("monto","<",0);
        })
        ->when($qcuentasPorPagarTipoFact!="",function($q) use ($qcuentasPorPagarTipoFact,$today){
            switch ($qcuentasPorPagarTipoFact) {
                case "abonos":
                    $q->where("monto",">",0);
                break;
                case "pagadas":
                    $q->havingRaw("monto_abonado = monto*-1")
                    ->where("monto","<",0);
                break;
                case "semipagadas":
                    $q
                    ->havingRaw("COALESCE(monto_abonado, 0) > 0")
                    ->havingRaw("COALESCE(monto_abonado, 0) <> monto*-1")
                    ->where("monto","<",0);
                break;
                case "porvencer":
                    $q->where("fechavencimiento",">",$today)
                    ->havingRaw("COALESCE(monto_abonado, 0) <> monto*-1")
                    ->havingRaw("COALESCE(monto_abonado, 0) = 0")
                    ->where("monto","<",0);
                break;
                case "vencidas":
                    $q
                    ->where("fechavencimiento","<=",$today)
                    ->havingRaw("COALESCE(monto_abonado, 0) <> monto*-1")
                    ->where("monto","<",0);
                break;
            }
        })

        ->orderBy($qCampocuentasPorPagarDetalles,$OrdercuentasPorPagarDetalles);

        $detalles_modified = $detalles->get()->map(function($q) use($today,$qcuentasPorPagarTipoFact, $todayWithoutDateTime) {
            $descuento = 0;
            if ($q->descuento) {
                $descuento = $q->monto*($q->descuento/100);
            }
            $q->monto_descuento = $descuento;
            $q->monto_bruto = $q->monto;

            $q->monto = $q->monto-$descuento;
            $fechavencimiento = new \DateTime($q->fechavencimiento);
            $monto_abonado = $q->monto_abonado?$q->monto_abonado:0;
            $monto = $q->monto;

            $q->balance = floatval($q->monto_abonado)+floatval($q->monto);

            $hoy = new \DateTime($todayWithoutDateTime);
            $vence = new \DateTime($q->fechavencimiento);
            $interval = $hoy->diff($vence);
            $q->dias = $interval->format('%R%a');

            if (($qcuentasPorPagarTipoFact=="abonos"|| $qcuentasPorPagarTipoFact=="") && $monto>0){
                $q->condicion = "abonos";
            }else if (($qcuentasPorPagarTipoFact=="vencidas"|| $qcuentasPorPagarTipoFact=="") && $fechavencimiento<=$today && $monto_abonado!=$monto*-1 && $monto<0){
                $q->condicion = "vencidas";
            }else if(($qcuentasPorPagarTipoFact=="porvencer"|| $qcuentasPorPagarTipoFact=="") && $fechavencimiento>$today && $monto_abonado!=$monto*-1 && $monto_abonado==0 && $monto<0){
                $q->condicion = "porvencer";
            }else if(($qcuentasPorPagarTipoFact=="pagadas"|| $qcuentasPorPagarTipoFact=="") && $monto_abonado==$monto*-1 && $monto<0){
                $q->condicion = "pagadas";
            }else if(($qcuentasPorPagarTipoFact=="semipagadas"|| $qcuentasPorPagarTipoFact=="") && $monto_abonado>0 && $monto_abonado!=$monto*-1 && $monto<0){
                $q->condicion = "semipagadas";
            }
            return $q;
        }); 
        $ret =  [
            "detalles" => $detalles_modified, 
            "balance" => $detalles_modified->sum("balance"), 
            "sum" => $detalles->get()->count(), 
        ];

        if ($type=="buscar") {
            return $ret;
        }else{
            return view("reportes.conciliacionCuentasxPagar",$ret);
        }
    }
}
