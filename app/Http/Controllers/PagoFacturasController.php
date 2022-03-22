<?php

namespace App\Http\Controllers;

use App\Models\pago_facturas;
use App\Http\Requests\Storepago_facturasRequest;
use App\Http\Requests\Updatepago_facturasRequest;

class PagoFacturasController extends Controller
{
    public function getPagoProveedor(Request $req)
    {
        $id_proveedor = $req->id_proveedor;
        $facturas = factura::where("id_proveedor",$id_proveedor)->orderBy("created_at","desc")->get();
        $pagos = pago_facturas::where("id_proveedor",$id_proveedor)->orderBy("created_at","desc")->get();
        $balance = 0;
        return collect(collect($pagos->merge($facturas))->sortBy([
            ['created_at', 'asc'],
        ])->map(function($q) use (&$balance){
            if (isset($q->numfact)) {
                $balance -= $q->monto;
            }else{
                
                $balance += $q->monto;
            }
            $q->balance = $balance;

            return $q;
        }))->sortBy([
            ['created_at', 'desc'],
        ]);

    }
   public function setPagoProveedor(Request $req)
   {
       try {
            $pago = new pago_facturas;
            $pago->tipo = $req->tipo;
            $pago->monto = $req->monto;
            $pago->id_proveedor = $req->id_proveedor;
            $pago->save();
            return Response::json(["msj"=>"¡Éxito al agregar pago a proveedor!","estado"=>true]);

            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
   } 
}
