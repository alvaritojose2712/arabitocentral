<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Http\Requests\StorecategoriasRequest;
use App\Http\Requests\UpdatecategoriasRequest;
use Illuminate\Http\Request;
use Response;

class CategoriasController extends Controller
{
        public function getCategorias(Request $req)
        {
            try {
                $q = $req->q;
                return categorias::where("descripcion","LIKE",$q."%")->orderBy("descripcion","asc")->get(["id","descripcion"]);
                
            } catch (\Exception $e) {
                return [];
            }
        }
    
        public function delCategoria(Request $req)
        {
            try {
                $id = $req->id;
                if ($id) {
                    categorias::find($id)->delete();
                }
                return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);
                
            } catch (\Exception $e) {
                return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
                
            }
        }
        public function setCategorias(Request $req)
        {
            try {
                categorias::updateOrCreate(
                    ["id"=>$req->id],[
                        "descripcion"=>$req->categoriasDescripcion,
                    ]);
                return Response::json(["msj"=>"¡Éxito!","estado"=>true]);
            } catch (\Exception $e) {
                return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            }
        }
}
