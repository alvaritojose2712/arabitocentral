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
        $b = cuentasporpagar::selectRaw("@monto_condescuento := SUM((1-(COALESCE(descuento,0)/100))*monto) AS monto_condescuento")
        ->where("id_proveedor", $id_proveedor)
        ->where("aprobado",$cuentaporpagarAprobado)
        ->first("monto_condescuento");
        if ($b) {
            return $b->monto_condescuento;
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

        $montobs1PagoFact = $req->montobs1PagoFact;
        $tasabs1PagoFact = $req->tasabs1PagoFact;
        $metodobs1PagoFact = $req->metodobs1PagoFact;
        $refbs1PagoFact = $req->refbs1PagoFact;
        $montobs2PagoFact = $req->montobs2PagoFact;
        $tasabs2PagoFact = $req->tasabs2PagoFact;
        $metodobs2PagoFact = $req->metodobs2PagoFact;
        $refbs2PagoFact = $req->refbs2PagoFact;
        $montobs3PagoFact = $req->montobs3PagoFact;
        $tasabs3PagoFact = $req->tasabs3PagoFact;
        $metodobs3PagoFact = $req->metodobs3PagoFact;
        $refbs3PagoFact = $req->refbs3PagoFact;
        $montobs4PagoFact = $req->montobs4PagoFact;
        $tasabs4PagoFact = $req->tasabs4PagoFact;
        $metodobs4PagoFact = $req->metodobs4PagoFact;
        $refbs4PagoFact = $req->refbs4PagoFact;
        $montobs5PagoFact = $req->montobs5PagoFact;
        $tasabs5PagoFact = $req->tasabs5PagoFact;
        $metodobs5PagoFact = $req->metodobs5PagoFact;
        $refbs5PagoFact = $req->refbs5PagoFact;

        if ($montobs1PagoFact) {
            if (!$tasabs1PagoFact || !$metodobs1PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 1"];
            }
        }
        if ($montobs2PagoFact) {
            if (!$tasabs2PagoFact || !$metodobs2PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 2"];
            }
        }
        if ($montobs3PagoFact) {
            if (!$tasabs3PagoFact || !$metodobs3PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 3"];
            }
        }
        if ($montobs4PagoFact) {
            if (!$tasabs4PagoFact || !$metodobs4PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 4"];
            }
        }
        if ($montobs5PagoFact) {
            if (!$tasabs5PagoFact || !$metodobs5PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 5"];
            }
        }
        if ($selectAbonoFact) {
            foreach ($selectAbonoFact as $abonoFact) {
                $c = cuentasporpagar::with("proveedor")->find($abonoFact["id"]);
                if ($c) {
                    if ($c->proveedor->id!=$id_pro) {
                        return ["estado"=>false,"msj"=>$c->numfact." no es del Proveedor Seleccionado en el Abono"];
                    }
                }
            }
        }

        $id = isset($req->id)? $req->id: null;
        $id_sucursal = isset($req->id_sucursal)? $req->id_sucursal: null;

        if (
            !$cuentasPagosDescripcion || 
            !$cuentasPagosMonto || 
            !$cuentasPagosFecha 
        ) {
            return [
                "estado" => false,
                "msj" => "Error: Campos vacíos",
                "id_proveedor" => $id_pro,
                "cuentasPagosDescripcion" => $cuentasPagosDescripcion,
                "cuentasPagosMonto" => $cuentasPagosMonto,
                "cuentasPagosFecha" => $cuentasPagosFecha,
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

                "montobs1PagoFact" => $montobs1PagoFact,
                "tasabs1PagoFact" => $tasabs1PagoFact,
                "metodobs1PagoFact" => $metodobs1PagoFact,
                "montobs2PagoFact" => $montobs2PagoFact,
                "tasabs2PagoFact" => $tasabs2PagoFact,
                "metodobs2PagoFact" => $metodobs2PagoFact,
                "montobs3PagoFact" => $montobs3PagoFact,
                "tasabs3PagoFact" => $tasabs3PagoFact,
                "metodobs3PagoFact" => $metodobs3PagoFact,
                "montobs4PagoFact" => $montobs4PagoFact,
                "tasabs4PagoFact" => $tasabs4PagoFact,
                "metodobs4PagoFact" => $metodobs4PagoFact,
                "montobs5PagoFact" => $montobs5PagoFact,
                "tasabs5PagoFact" => $tasabs5PagoFact,
                "metodobs5PagoFact" => $metodobs5PagoFact,

                "refbs1PagoFact" => $refbs1PagoFact,
                "refbs2PagoFact" => $refbs2PagoFact,
                "refbs3PagoFact" => $refbs3PagoFact,
                "refbs4PagoFact" => $refbs4PagoFact,
                "refbs5PagoFact" => $refbs5PagoFact,
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

        $montobs1PagoFact = isset($arr["montobs1PagoFact"])? $arr["montobs1PagoFact"]: 0;
        $tasabs1PagoFact = isset($arr["tasabs1PagoFact"])? $arr["tasabs1PagoFact"]: 0;
        $metodobs1PagoFact = isset($arr["metodobs1PagoFact"])? $arr["metodobs1PagoFact"]: 0;
        $montobs2PagoFact = isset($arr["montobs2PagoFact"])? $arr["montobs2PagoFact"]: 0;
        $tasabs2PagoFact = isset($arr["tasabs2PagoFact"])? $arr["tasabs2PagoFact"]: 0;
        $metodobs2PagoFact = isset($arr["metodobs2PagoFact"])? $arr["metodobs2PagoFact"]: 0;
        $montobs3PagoFact = isset($arr["montobs3PagoFact"])? $arr["montobs3PagoFact"]: 0;
        $tasabs3PagoFact = isset($arr["tasabs3PagoFact"])? $arr["tasabs3PagoFact"]: 0;
        $metodobs3PagoFact = isset($arr["metodobs3PagoFact"])? $arr["metodobs3PagoFact"]: 0;
        $montobs4PagoFact = isset($arr["montobs4PagoFact"])? $arr["montobs4PagoFact"]: 0;
        $tasabs4PagoFact = isset($arr["tasabs4PagoFact"])? $arr["tasabs4PagoFact"]: 0;
        $metodobs4PagoFact = isset($arr["metodobs4PagoFact"])? $arr["metodobs4PagoFact"]: 0;
        $montobs5PagoFact = isset($arr["montobs5PagoFact"])? $arr["montobs5PagoFact"]: 0;
        $tasabs5PagoFact = isset($arr["tasabs5PagoFact"])? $arr["tasabs5PagoFact"]: 0;
        $metodobs5PagoFact = isset($arr["metodobs5PagoFact"])? $arr["metodobs5PagoFact"]: 0;

        $refbs1PagoFact = isset($arr["refbs1PagoFact"])? $arr["refbs1PagoFact"]: 0;
        $refbs2PagoFact = isset($arr["refbs2PagoFact"])? $arr["refbs2PagoFact"]: 0;
        $refbs3PagoFact = isset($arr["refbs3PagoFact"])? $arr["refbs3PagoFact"]: 0;
        $refbs4PagoFact = isset($arr["refbs4PagoFact"])? $arr["refbs4PagoFact"]: 0;
        $refbs5PagoFact = isset($arr["refbs5PagoFact"])? $arr["refbs5PagoFact"]: 0;
        
        
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

            "montobs1" => $montobs1PagoFact,
            "tasabs1" => $tasabs1PagoFact,
            "metodobs1" => $metodobs1PagoFact,
            "montobs2" => $montobs2PagoFact,
            "tasabs2" => $tasabs2PagoFact,
            "metodobs2" => $metodobs2PagoFact,
            "montobs3" => $montobs3PagoFact,
            "tasabs3" => $tasabs3PagoFact,
            "metodobs3" => $metodobs3PagoFact,
            "montobs4" => $montobs4PagoFact,
            "tasabs4" => $tasabs4PagoFact,
            "metodobs4" => $metodobs4PagoFact,
            "montobs5" => $montobs5PagoFact,
            "tasabs5" => $tasabs5PagoFact,
            "metodobs5" => $metodobs5PagoFact,

            "refbs1" => $refbs1PagoFact,
            "refbs2" => $refbs2PagoFact,
            "refbs3" => $refbs3PagoFact,
            "refbs4" => $refbs4PagoFact,
            "refbs5" => $refbs5PagoFact,
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
                        
                        $this->setEstatusFact($e["id"]);

                        
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
                            "msj" => "Desde Central: Éxito al registrar Factura ID_CENTRAL_FACT:".$registrarfactura->id,
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
            $q->balance = $b?$b:0; 

            $totalSum += $b;
            
            return $q; 
        })->toArray();

        $cuentasporpagarColumn = array_column($cuentasporpagar, 'balance');
        array_multisort($cuentasporpagarColumn, SORT_ASC, $cuentasporpagar);
        
        return [
            "cuentasporpagar" => collect($cuentasporpagar),
            "sum" => $totalSum,
        ];
    }
    function changeSucursal(Request $req) {
        
        $su = $req->sucursal;        
        $sucursal = sucursal::where("codigo",$su)->first();
        if ($sucursal) {
            $upd = cuentasporpagar::find($req->id);
            $upd->id_sucursal = $sucursal->id;
            return $upd->save();
        }else{
            return "No se encontró Sucursal";
        }
        
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
                
                $arrinsert["fechaemision"] = $newfactfechaemision;
                $arrinsert["fechavencimiento"] = $newfactfechavencimiento;
                $arrinsert["fecharecepcion"] = $newfactfecharecepcion;
                if (!$id) {
                    $arrinsert["id_proveedor"] = $newfactid_proveedor;
                    $arrinsert["id_sucursal"] = $su->id;
                }
                $search = ["id" => $id];
                $cu = $this->setCuentaPorPagar($arrinsert,$search);
                $this->setEstatusFact($cu->id);
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
    function setEstatusFact($id){
        $cuenta = cuentasporpagar::find($id);
        $monto_abonado = cuentasporpagar_pagos::where("id_factura", $id)->sum("monto");

        $monto = $cuenta->monto ? $cuenta->monto: 0;
        $descuento = $cuenta->descuento ? $cuenta->descuento: 0;

        $monto_descuento = $monto*(($descuento/100)?$descuento/100:0);
        $balance = $monto_abonado + $monto - $monto_descuento;
        $estatus = 0;
        if ($balance > -0.1 && $balance < 0.1) {
            $estatus = 2;
        }elseif ($monto_abonado>0) {
            $estatus = 1;
        }

        $cuenta->estatus = $estatus;
        $cuenta->updated_at = date("Y-m-d H:i:s");
        $cuenta->save();
    }
    function setEstatusAll(){
     $cuentas= cuentasporpagar::all();
     
     foreach ($cuentas as $key => $value) {
        $this->setEstatusFact($value->id);
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
        $id_facts_force = $req->id_facts_force;
        
        
        
        
        $todayWithoutDateTime = (new NominaController)->today();
        $today = new \DateTime($todayWithoutDateTime);
        $detalles = cuentasporpagar::with(["sucursal","proveedor","pagos"=>function($q) {
            
            $q->orderBy("id","desc");
        },"facturas"=>function($q) {

            $q->with(["sucursal","proveedor"])->orderBy("id","desc");
        }])
        ->selectRaw("*, @monto_abonado := ( SELECT sum(`cuentasporpagar_pagos`.`monto`) FROM cuentasporpagar_pagos WHERE `cuentasporpagar_pagos`.`id_factura` =`cuentasporpagars`.`id` ) as monto_abonado, 
        @monto_descuento := (COALESCE(monto,0)*(COALESCE(descuento,0)/100)) as monto_descuento,
        (COALESCE(@monto_abonado,0)+COALESCE(monto,0)-COALESCE(@monto_descuento,0)) as balance
        ");
        if ($id_facts_force) {
            $detalles = $detalles->whereIn("id",$id_facts_force);
            
        }else{
            $detalles = $detalles
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
                    $q->where("monto","<=",0);
                }else{
                    $q->where("monto",">",0);
                }
            })
            ->when($qcuentasPorPagarTipoFact=="",function($q) {
                $q->where("monto","<=",0)
                ->where("estatus","<>",2);
            })
            ->when($qcuentasPorPagarTipoFact!="",function($q) use ($qcuentasPorPagarTipoFact,$today){
                switch ($qcuentasPorPagarTipoFact) {
                    case "abonos":
                        $q->where("monto",">",0);
                    break;
                    case "pagadas":
                        $q->where("monto", "<=", "0")
                        ->where("estatus",2);
                        break;
                    case "semipagadas":
                        $q
                        ->where("estatus",1)
                        ->where("monto","<=",0);
                    break;
                    case "porvencer":
                        $q->where("fechavencimiento",">",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                    case "vencidas":
                        $q
                        ->where("fechavencimiento","<=",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                }
            })
            ->orderBy($qCampocuentasPorPagarDetalles,$OrdercuentasPorPagarDetalles);
        }

        $detalles_modified = $detalles->get()->map(function($q) use($today,$qcuentasPorPagarTipoFact, $todayWithoutDateTime) {
            $q->monto_bruto = $q->monto;
            $q->monto = $q->monto-$q->monto_descuento;

            $fechavencimiento = new \DateTime($q->fechavencimiento);
            $monto_abonado = $q->monto_abonado?$q->monto_abonado:0;
            $monto = $q->monto;
            $balance = $q->balance;
            
            $hoy = new \DateTime($todayWithoutDateTime);
            $vence = new \DateTime($q->fechavencimiento);
            $interval = $hoy->diff($vence);
            
            $q->dias = $interval->format('%R%a');

            if ($monto>0){
                $q->condicion = "abonos";
            
            }else if($q->estatus==1 && $monto<0){
                $q->condicion = "semipagadas";
            
            }else if($q->estatus==2 && $monto< 0){
                $q->condicion = "pagadas";
            
            }else if ($fechavencimiento<=$today && $q->estatus==0 && $monto<0){
                $q->condicion = "vencidas";
            
            }else if($fechavencimiento>$today && $q->estatus==0 && $monto<0){
                $q->condicion = "porvencer";
            }
            return $q;
        }); 
        $ret =  [
            "detalles" => $detalles_modified, 
            "balance" => $detalles_modified->sum("balance"), 
            "sum" => $detalles->get()->count(), 
            "proveedor" => $id_proveedor?proveedores::find($id_proveedor):null
        ];

        if ($type=="buscar") {
            return $ret;
        }else{
            return view("reportes.conciliacionCuentasxPagar",$ret);
        }
    }
}
