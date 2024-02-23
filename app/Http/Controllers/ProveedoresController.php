<?php

namespace App\Http\Controllers;

use App\Models\proveedores;
use App\Http\Requests\StoreproveedoresRequest;
use App\Http\Requests\UpdateproveedoresRequest;

use Illuminate\Http\Request;
use Response;

class ProveedoresController extends Controller
{
   public function setProveedor(Request $req)
    {   
        try {
            $id = $req->id;
            $proveedordescripcion = $req->proveedordescripcion;
            $proveedorrif = $req->proveedorrif;
            $proveedordireccion = $req->proveedordireccion;
            $proveedortelefono = $req->proveedortelefono;

            proveedores::updateOrCreate(
                [
                    "id" => $id,
                ],
                [
                    "descripcion" => $proveedordescripcion,
                    "rif" => $proveedorrif,
                    "direccion" => $proveedordireccion,
                    "telefono" => $proveedortelefono,
                ]

            );
            return Response::json(["msj"=>"Éxito","estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
    }
    public function getProveedores(Request $req)
    {
        return proveedores::where("descripcion","LIKE","%".$req->q."%")->orwhere("rif","LIKE","%".$req->q."%")->orderBy("descripcion","desc")->get();
        
    }

    public function getAllProveedores()
    {
        return proveedores::orderBy("descripcion","asc")->get();
        
    }

    public function delProveedor(Request $req)
    {
        try {
            $id = $req->id;
            proveedores::find($id)->delete();
            return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);

            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
}
