<?php

namespace App\Http\Controllers;

use App\Models\alquileres;
use App\Http\Requests\StorealquileresRequest;
use App\Http\Requests\UpdatealquileresRequest;
use Illuminate\Http\Request;
use Response;

class AlquileresController extends Controller
{
    function delAlquiler(Request $req) {
        try {
            $id = $req->id;
            $del = alquileres::find($id)->delete();
            if ($del) {
                return Response::json(["msj"=>"Éxito", "estado"=>true]);
            }
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(), "estado"=>false]);
        }

    }
    function getAlquileres(Request $req) {
        $query = $req->q;
        $q_sucursal = $req->q_sucursal;

        $data = [];

        $data = alquileres::with("sucursal")
        ->when($query, function($q) use ($query){
            $q->where("descripcion",$query);
        })
        ->when($q_sucursal, function($q) use ($q_sucursal){
            $q->where("id_sucursal",$q_sucursal);
        })
        ->orderBy("id_sucursal","asc");
        
        return [
            "data" => $data->get(),
            "sum" => $data->sum("monto"),
        ];
    }
    function setNewAlquiler(Request $req) {
        try {
            $sendalquilerdesc = $req->sendalquilerdesc;
            $sendalquilermonto = $req->sendalquilermonto;
            $sendalquilersucursal = $req->sendalquilersucursal;
    
            $sendalquilerid = $req->sendalquilerid;
    
            $a = alquileres::updateOrCreate(["id"=>$sendalquilerid],[
                "descripcion" => $sendalquilerdesc,
                "monto" => $sendalquilermonto,
                "id_sucursal" => $sendalquilersucursal,
            ]);
    
            return Response::json(["msj"=>"Éxito","estado"=>true]);
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error","estado"=>false]);
        }
    }

    function getAlquileresSucursal(Request $req) {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];


        return alquileres::with("sucursal")
        ->where("id_sucursal",$id_sucursal)
        ->orderBy("id_sucursal","asc")
        ->get();
    }
}
