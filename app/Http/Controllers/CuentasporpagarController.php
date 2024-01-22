<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar;
use App\Http\Requests\StorecuentasporpagarRequest;
use App\Http\Requests\UpdatecuentasporpagarRequest;
use App\Models\proveedores;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Response;


class CuentasporpagarController extends Controller
{

    function getBalance($id_proveedor){
        $b = cuentasporpagar::where("id_proveedor", $id_proveedor)->sum("monto");
        if ($b) {
            return $b;
        }
        return 0;
    }
    function sendPagoCuentaPorPagar(Request $req) {
        $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
        $cuentasPagosMonto = $req->cuentasPagosMonto;
        $cuentasPagosMetodo = $req->cuentasPagosMetodo;
        $cuentasPagosFecha = $req->cuentasPagosFecha;
        $id_pro = $req->id_pro;
        $selectAbonoFact = $req->selectAbonoFact	;
        
        

        $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
            "nombre" => "ADMINISTRACION",
            "codigo" => "administracion",
        ]);

        if ($su) {
            $today = (new NominaController)->today();

            $pago = $this->setPago([
                "id_sucursal" => $su->id,
                "idinsucursal_pago" => time(),
                "id_proveedor_caja" => $id_pro,
                "numfact_desc" => $cuentasPagosDescripcion,
                "monto" => $cuentasPagosMonto,
                "fecha_creada" => $cuentasPagosFecha,
                "metodo" => $cuentasPagosMetodo,
                "selectAbonoFact" =>$selectAbonoFact,
                
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
        ];
        $search = [
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $idinsucursal_pago
        ];
        
        $cuenta = $this->setCuentaPorPagar($arrinsert,$search);        
        
        if ($cuenta) {
            if ($selectAbonoFact) {
                if (count($selectAbonoFact)) {
                    foreach ($selectAbonoFact as $e) {
                        $update_cuenta = cuentasporpagar::find($e["id"]);
                        $update_cuenta->monto_abonado = $e["val"];
                        $update_cuenta->id_cuentaporpagar = $cuenta->id;
                        if ($update_cuenta->save()) {
                            return $cuenta;
                        }
                    }
                }
            }
        }
    }
    function sendFacturaCentral(Request $req){
        try {
       
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];


            $factura = $req->factura;

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

    function getCuentas($fechasMain1, $fechasMain2, $id_sucursal, $filtros){
        $qcuentasPorPagar = $filtros["qcuentasPorPagar"];
        $cuentasporpagar = proveedores::when($qcuentasPorPagar!="",function($q) use ($qcuentasPorPagar){
            $q->orWhere("descripcion","LIKE","%$qcuentasPorPagar%")
            ->orWhere("rif","LIKE","%$qcuentasPorPagar%");
        })
        ->get()
        ->map(function($q){
            $balance_query = cuentasporpagar::where("id_proveedor",$q->id_proveedor)->orderBy("id","desc")->first();
            $balance = 0;
            if ($balance_query) {
                $balance = $balance_query->balance;
            }
            $q->balance = $balance; 
            return $q; 
        })->toArray();

        $cuentasporpagarColumn = array_column($cuentasporpagar, 'balance');
        array_multisort($cuentasporpagarColumn, SORT_DESC, $cuentasporpagar);
        
        return [
            "cuentasporpagar" => $cuentasporpagar,
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
                $arrinsert = [
                    "id_proveedor" => $newfactid_proveedor,
                    "id_sucursal" => $su->id,
                    "numfact" => $newfactnumfact,
                    "numnota" => $newfactnumnota,
                    "descripcion" => $newfactdescripcion,
                    "subtotal" => $newfactsubtotal,
                    "descuento" => $newfactdescuento,
                    "monto_exento" => $newfactmonto_exento,
                    "monto_gravable" => $newfactmonto_gravable,
                    "iva" => $newfactiva,
                    "monto" => $newfactmonto,
                    "fechaemision" => $newfactfechaemision,
                    "fechavencimiento" => $newfactfechavencimiento,
                    "fecharecepcion" => $newfactfecharecepcion,
                    "nota" => $newfactnota,
                    "tipo" => $newfacttipo,
                    "frecuencia" => $newfactfrecuencia,
                ];
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

    function selectCuentaPorPagarProveedorDetalles(Request $req) {
        $id = $req->id;
        $qCampocuentasPorPagarDetalles = $req->qCampocuentasPorPagarDetalles;
        $qcuentasPorPagarDetalles = $req->qcuentasPorPagarDetalles;


        $qFechaCampocuentasPorPagarDetalles = $req->qFechaCampocuentasPorPagarDetalles;
        $fechacuentasPorPagarDetalles = $req->fechacuentasPorPagarDetalles;
        $categoriacuentasPorPagarDetalles = $req->categoriacuentasPorPagarDetalles;
        $tipocuentasPorPagarDetalles = $req->tipocuentasPorPagarDetalles;

        $OrdercuentasPorPagarDetalles = $req->OrdercuentasPorPagarDetalles;
        $OrderFechacuentasPorPagarDetalles = $req->OrderFechacuentasPorPagarDetalles;
        
        $detalles = cuentasporpagar::with(["sucursal","proveedor","cuenta"])
        ->where("id_proveedor",$id)
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
        ->when($qcuentasPorPagarDetalles!="",function($q) use ($qcuentasPorPagarDetalles,$qCampocuentasPorPagarDetalles){
            $q->where($qCampocuentasPorPagarDetalles,"LIKE","%$qcuentasPorPagarDetalles%");
        })
        ->when($fechacuentasPorPagarDetalles!="",function($q) use ($qFechaCampocuentasPorPagarDetalles,$fechacuentasPorPagarDetalles) {
            $q->where($qFechaCampocuentasPorPagarDetalles,"LIKE","%$fechacuentasPorPagarDetalles%");
        })
        ->when($qcuentasPorPagarDetalles!="",function($q) use ($qCampocuentasPorPagarDetalles, $OrdercuentasPorPagarDetalles){
            $q->orderBy($qCampocuentasPorPagarDetalles,$OrdercuentasPorPagarDetalles);
        })
        ->when($qFechaCampocuentasPorPagarDetalles=="created_at" && $fechacuentasPorPagarDetalles=="",function($q) use ($qCampocuentasPorPagarDetalles, $OrdercuentasPorPagarDetalles){
            $q->orderBy("created_at","desc");
        });
        

        $balance = $this->getBalance($id);

        return [
            "detalles" => $detalles->get(), 
            "balance" => $balance, 
        ];
    }
}
