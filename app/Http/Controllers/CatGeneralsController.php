<?php

namespace App\Http\Controllers;

use App\Models\CatGenerals;
use App\Http\Requests\StoreCatGeneralsRequest;
use App\Http\Requests\UpdateCatGeneralsRequest;
use Illuminate\Http\Request;
use Response;

class CatGeneralsController extends Controller
{
    public function getCatGenerals(Request $req)
    {
        try {
            $q = $req->q;
            return CatGenerals::where("descripcion","LIKE",$q."%")->orderBy("descripcion","asc")->get(["id","descripcion"]);
            
        } catch (\Exception $e) {
            return [];
        }
    }

    public function delCatGeneral(Request $req)
    {
        try {
            $id = $req->id;
            if ($id) {
                CatGenerals::find($id)->delete();
            }
            return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    public function setCatGenerals(Request $req)
    {
        try {
            CatGenerals::updateOrCreate(
                ["id"=>$req->id],[
                    "descripcion"=>$req->catGeneralsDescripcion,
                ]);
            return Response::json(["msj"=>"¡Éxito!","estado"=>true]);
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
    }
}
