<?php

namespace App\Http\Controllers;

use App\Models\facturas;
use App\Http\Requests\StorefacturasRequest;
use App\Http\Requests\UpdatefacturasRequest;

use Illuminate\Http\Request;
use Response;

class FacturasController extends Controller
{
    
   public function verFactura(Request $req)
    {
        $id = $req->id;
        $factura = facturas::with(["proveedor","items"=>function($q){
            $q->with("producto");
        }])
        ->find($id);

        $factura->items->map(function($q)
        {   
            $q->subtotal = number_format($q->producto->precio*$q->cantidad,2);
            return $q;
        });
        $factura->monto = number_format($factura->monto,2);

        $sucursal = sucursal::all()->first();

        return view("reportes.factura",["factura"=>$factura,"sucursal"=>$sucursal]);
    }
    public function getFacturas(Request $req)
    {
        $factqBuscar = $req->factqBuscar;
        $factqBuscarDate = $req->factqBuscarDate;
        $factOrderBy = $req->factOrderBy;
        $factOrderDescAsc = $req->factOrderDescAsc;

        $fa = [];
        if ($factqBuscarDate=="") {
            $fa = facturas::with(["proveedor","items"=>function($q){
                $q->with("producto");
            }])
            ->where("descripcion","LIKE","$factqBuscar%")
            ->orWhere("numfact","LIKE","$factqBuscar%")
                ->orderBy($factOrderBy,$factOrderDescAsc)
                ->limit(20)
                ->get();
        }else{
            $fa = facturas::with(["proveedor","items"=>function($q){
                $q->with("producto");
            }])->where("descripcion","LIKE","$factqBuscar%")->where("created_at","LIKE","$factqBuscarDate%")
                ->orderBy($factOrderBy,$factOrderDescAsc)
                ->limit(20)
                ->get();
        }

        return $fa->map(function($q){
            $sub = $q->items->map(function($q)
            {   
                $base = $q->producto->precio_base*$q->cantidad;
                $venta = $q->producto->precio*$q->cantidad;
                // $q->subtotal = number_format($venta,2);
                // $q->subtotal_base = number_format($base,2);

                $q->subtotal_clean = $venta;
                $q->subtotal_base_clean = $base;
                return $q;
            });
            
            $venta = $sub->sum("subtotal_clean");
            $base = $sub->sum("subtotal_base_clean");

            // $q->summonto = number_format($venta,2); 
            $q->summonto_clean = $venta; 


            // $q->summonto_base = number_format($base,2); 
            $q->summonto_base_clean = $base; 
            return $q;
        });
    }
    public function saveMontoFactura(Request $req)
    {
        $fact = facturas::find($req->id);
        $fact->monto = $req->monto;
        $fact->save();
    }
    public function setFactura(Request $req)
    {
        try {
            $id = $req->id;
            $factInpid_proveedor = $req->factInpid_proveedor;
            $factInpnumfact = $req->factInpnumfact;
            $factInpdescripcion = $req->factInpdescripcion;
            $factInpmonto = $req->factInpmonto;
            $factInpfechavencimiento = $req->factInpfechavencimiento;
            $factInpestatus = $req->factInpestatus;



            facturas::updateOrCreate(
                [
                    "id" => $id,
                ],
                [
                    "id_proveedor" => $factInpid_proveedor,
                    "numfact" => $factInpnumfact,
                    "descripcion" => $factInpdescripcion,
                    "monto" => $factInpmonto,
                    "fechavencimiento" => $factInpfechavencimiento,
                    "estatus" => $factInpestatus,

                ]

            );
            return Response::json(["msj"=>"Éxito","estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
      
    }

    public function delFactura(Request $req)
    {
        try {
            $id = $req->id;
            facturas::find($id)->delete();
            return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);

            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }

}
