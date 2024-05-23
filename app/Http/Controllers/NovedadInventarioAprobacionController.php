<?php

namespace App\Http\Controllers;

use App\Models\novedad_inventario_aprobacion;
use Illuminate\Http\Request;
use Response;


class NovedadInventarioAprobacionController extends Controller
{

    function resolveNovedadCentralCheck(Request $req) {
        $id = $req->resolveNovedadId;
        $n = novedad_inventario_aprobacion::where("idinsucursal",$id)->first();

        if ($n) {
            if ($n->estado) {
                return [
                    "estado"=>true,
                    "msj"=>"Novedad aprobada!",
                    "idinsucursal"=>$id,
                    "productoAprobado" => $n
                ];
            }
        }
        return ["estado"=>false,"msj"=>"Rechazado!"];

    }
    function delInventarioNovedades(Request $req) {
        $id = $req->id;
        $n = novedad_inventario_aprobacion::find($id);   
        if ($n->delete()) {
            return Response::json(["msj"=>"Eliminado","estado"=>true]);
        }     
    }
    function getInventarioNovedades(Request $req) {
        $qInventarioNovedades = $req->qInventarioNovedades;
        $qFechaInventarioNovedades = $req->qFechaInventarioNovedades;
        $qFechaHastaInventarioNovedades = $req->qFechaHastaInventarioNovedades;
        $qSucursalInventarioNovedades = $req->qSucursalInventarioNovedades;

        $n = novedad_inventario_aprobacion::with("sucursal")
        ->when($qInventarioNovedades, function($q) use ($qInventarioNovedades){
            $q->orwhere("responsable","LIKE","%$qInventarioNovedades%")
            ->orwhere("responsable","LIKE","%$qInventarioNovedades%");
        })
        ->when($qFechaInventarioNovedades, function($q) use ($qFechaHastaInventarioNovedades,$qFechaInventarioNovedades){
            $q->whereBetween("created_at",[$qFechaInventarioNovedades." 00:00:00",(!$qFechaHastaInventarioNovedades?$qFechaInventarioNovedades:$qFechaHastaInventarioNovedades)." 23:59:59"]);
        })
        ->when($qSucursalInventarioNovedades, function($q) use ($qSucursalInventarioNovedades) {
            $q->when("id_sucursal",$qSucursalInventarioNovedades);
        })
        ->orderBy("updated_at","desc")
        ->get();

        return [
            "data" => $n
        ];
    }
    function resolveInventarioNovedades(Request $req) {
        $id = $req->id;
        $n = novedad_inventario_aprobacion::find($id);
        if (!$n->estado) {
            $n->estado = 1;
        }else{
            $n->estado = 0;
        }
        $n->save();

    }
}
