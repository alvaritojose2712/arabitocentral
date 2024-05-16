<?php

namespace App\Http\Controllers;

use App\Models\usuarios;
use App\Http\Requests\StoreusuariosRequest;
use App\Http\Requests\UpdateusuariosRequest;
use Illuminate\Http\Request;
use Response;
use Hash;

class UsuariosController extends Controller
{
    public function getUsuarios(Request $req)
    {
        $qBuscarUsuario = $req->q;
        return usuarios::orwhere("usuario","LIKE",$qBuscarUsuario."%")->orwhere("nombre","LIKE",$qBuscarUsuario."%")->get(["id","nombre","usuario","tipo_usuario","area"]);
    }
    function importarusers(Request $req) {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];

        return usuarios::orwhere("id_sucursal",$id_sucursal)->orWhere("tipo_usuario",1)->get();

    }
    public function setUsuario(Request $req)
    {
        try {
            $arr = [
                "nombre"=>$req->nombres,
                "usuario"=>$req->usuario,
                "tipo_usuario"=>$req->role,
                "area"=>$req->area,
                "id_sucursal"=>$req->id_sucursal,
                
            ];
            if ($req->clave) {
                $arr["clave"] = Hash::make($req->clave);
            }

            usuarios::updateOrCreate(["id"=>$req->id],$arr);
            return Response::json(["msj"=>"¡Éxito!","estado"=>true]);
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
    }

    public function delUsuario(Request $req)
    {
        try {
            $id = $req->id;
            if ($id) {
                usuarios::find($id)->delete();
            }
            return Response::json(["msj"=>"Éxito al eliminar usuario.","estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
}
