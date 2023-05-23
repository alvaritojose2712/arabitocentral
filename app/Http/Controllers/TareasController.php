<?php

namespace App\Http\Controllers;

use App\Models\tareas;
use App\Http\Requests\StoretareasRequest;
use App\Http\Requests\UpdatetareasRequest;
use Illuminate\Http\Request;
use Response;


class TareasController extends Controller
{
    public function getTareasCentral(Request $req)
    {
        $codigo_origen = $req->codigo_origen;
        $estado = $req->estado;

        if (count($estado)==2) {
            return tareas::with(["origen"=>function($q){
                $q->select(["id","codigo","nombre"]);
            },"destino"=>function($q){
                $q->select(["id","codigo","nombre"]);
            }])->whereIn("estado",[0,1])->orderBy("origen","asc")->get(["id","origen","destino","solicitud","accion","estado","respuesta"]);
        }
        if (count($estado)==1) {
            //Tareas Pendientes //Solo se muestra la sucursal receptora
            return tareas::with(["origen"=>function($q){
                $q->select(["id","codigo","nombre"]);
            },"destino"=>function($q){
                $q->select(["id","codigo","nombre"]);
            }])->where("destino",function($q) use ($codigo_origen){
                $q->from("sucursals")->where("codigo",$codigo_origen)->select("id");
            })->whereIn("estado",[0,2])->get(["id","origen","destino","solicitud","accion","estado","respuesta"]);
        }
    }

    public function resolveTareaCentral(Request $req)
    {
        $id_tarea = $req->id_tarea;
        $respuesta = $req->respuesta;
        $estado = $req->estado;

        $tarea = tareas::find($id_tarea);
        $tarea->respuesta = $respuesta;
        $tarea->estado = $estado;

        if ($tarea->save()) {
            return "Desde Central: Éxito al resolver tarea.";
        }

    }
}
