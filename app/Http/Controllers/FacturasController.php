<?php

namespace App\Http\Controllers;

use App\Models\facturas;
use App\Http\Requests\StorefacturasRequest;
use App\Http\Requests\UpdatefacturasRequest;

use Illuminate\Http\Request;
use Response;

class FacturasController extends Controller
{
    
    public function getFacturas(Request $req)
    {
        $factqBuscar = $req->factqBuscar;
        $factqBuscarDate = $req->factqBuscarDate;
        $factOrderBy = $req->factOrderBy;
        $factOrderDescAsc = $req->factOrderDescAsc;

        if ($factqBuscarDate=="") {
            return facturas::with(["proveedor","items"=>function($q){
                $q->with("producto");
            },"producto"])
            ->where("descripcion","LIKE","$factqBuscar%")
            ->orWhere("numfact","LIKE","$factqBuscar%")
                ->orderBy($factOrderBy,$factOrderDescAsc)
                ->get();
        }else{
            return facturas::with(["proveedor","items"=>function($q){
                $q->with("producto");
            },"producto"])->where("descripcion","LIKE","$factqBuscar%")->where("created_at","LIKE","$factqBuscarDate%")
                ->orderBy($factOrderBy,$factOrderDescAsc)
                ->get();
        }
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
