<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar;
use App\Http\Requests\StorecuentasporpagarRequest;
use App\Http\Requests\UpdatecuentasporpagarRequest;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Response;


class CuentasporpagarController extends Controller
{

    function getBalance($id_proveedor){
        $b = cuentasporpagar::where("id_proveedor", $id_proveedor)->orderBy("id", "desc")->first(["balance"]);
        if ($b) {
            return $b["balance"];
        }
        return 0;
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

                    $registrarfactura = cuentasporpagar::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $factura["id"]
                    ],[
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
                        "balance" => $this->getBalance($id_proveedor->id) + ($factura["monto"]*$factor),
                        
                        
                        "fechaemision" => $factura["fechaemision"],
                        "fechavencimiento" => $factura["fechavencimiento"],
                        "fecharecepcion" => $factura["fecharecepcion"],
                        "nota" => $factura["nota"],
                    ]);
        
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
        $cuentasporpagar = proveedores::all()
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

    function selectCuentaPorPagarProveedorDetalles(Request $req) {
        $id = $req->id;
        $qcuentasPorPagarDetalles = $req->qcuentasPorPagarDetalles;
        
        $detalles = cuentasporpagar::with(["sucursal","proveedor"])
        ->where("id_proveedor",$id)
        ->when($qcuentasPorPagarDetalles,function($q) use ($qcuentasPorPagarDetalles){
            $q->orwhere("descripcion","LIKE","%$qcuentasPorPagarDetalles%")
            ->orwhere("numfact","LIKE","%$qcuentasPorPagarDetalles%")
            ->orwhere("numnota","LIKE","%$qcuentasPorPagarDetalles%");
        })
        ->get();

        return [
            "detalles" => $detalles, 
        ];
    }
}
