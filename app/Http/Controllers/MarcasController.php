<?php

namespace App\Http\Controllers;

use App\Models\marcas;
use App\Http\Requests\StoremarcasRequest;
use App\Http\Requests\UpdatemarcasRequest;
use Illuminate\Http\Request;
use Response;
class MarcasController extends Controller
{
    public function getMarcas(Request $req)
    {
        try {
            $q = $req->q;
            return marcas::where("descripcion","LIKE",$q."%")->orderBy("descripcion","asc")->get(["id","descripcion"]);
            
        } catch (\Exception $e) {
            return [];
        }
    }

    public function delMarca(Request $req)
    {
        try {
            $id = $req->id;
            if ($id) {
                marcas::find($id)->delete();
            }
            return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    public function setMarcas(Request $req)
    {
        try {
            marcas::updateOrCreate(
                ["id"=>$req->id],[
                    "descripcion"=>$req->marcasDescripcion,
                ]);
            return Response::json(["msj"=>"¡Éxito!","estado"=>true]);
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
    }
}
