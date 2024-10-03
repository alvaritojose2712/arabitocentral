<?php

namespace App\Http\Controllers;

use App\Models\tareasSucursales;
use App\Models\inventario_sucursal;
use App\Http\Requests\StoretareasSucursalesRequest;
use App\Http\Requests\UpdatetareasSucursalesRequest;
use Illuminate\Http\Request;


class TareasSucursalesController extends Controller
{
    function setTarea($ee,$tipo)  {
        if ($tipo==1) {
            $new = new tareasSucursales;
            $new->id_sucursal = $ee["id_sucursal"]; 
            $new->tipo = 1;
            $new->estado = 0;

            $antes = inventario_sucursal::find($ee["id"]);
            $new->antesproducto = json_encode($antes);

            unset($ee["categoria"]);
            unset($ee["proveedor"]);
            unset($ee["sucursal"]);
            unset($ee["tarea"]);
            unset($ee["sucursales"]);
            
            $new->cambiarproducto = json_encode($ee);
            $new->idinsucursal = $ee["idinsucursal"];
            
            $new->id_producto_verde = null;
            $new->id_producto_rojo = null;
            return $new->save();
        }
    }

    function getTareasPendientes(Request $req) {
        $qTareaPendienteFecha = $req->qTareaPendienteFecha;
        $qTareaPendienteSucursal = $req->qTareaPendienteSucursal;
        
        $qTareaPendienteNum = $req->qTareaPendienteNum;
        $qTareaPendienteEstado = $req->qTareaPendienteEstado;
        
        
        
        $data = tareasSucursales::with("sucursal")
        ->where("estado",$qTareaPendienteEstado)
        ->when($qTareaPendienteSucursal, function($q) use ($qTareaPendienteSucursal) {
            $q->where("id_sucursal",$qTareaPendienteSucursal);
        })
        ->when($qTareaPendienteFecha,function($q) {
            $q->where("created_at","LIKE","%$qTareaPendienteFecha%");
        })
        ->limit($qTareaPendienteNum)
        ->orderBy("created_at","desc")
        ->get()
        ->map(function($q) {
            $q->prodantesproducto = $q->antesproducto? json_decode($q->antesproducto,2):null;
            $q->prodcambiarproducto = $q->cambiarproducto? json_decode($q->cambiarproducto,2):null;
            return $q;
        });

        return [
            "data" => $data,
        ];
    }

    function getTareasCentral(Request $req) {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];

        $tareas = tareasSucursales::with("sucursal")
        ->where("id_sucursal",$id_sucursal)
        ->where("estado",0)
        ->orderBy("created_at","desc")
        ->get()
        ->map(function($q) {
            $q->prodantesproducto = $q->antesproducto? json_decode($q->antesproducto,2):null;
            $q->prodcambiarproducto = $q->cambiarproducto? json_decode($q->cambiarproducto,2):null;
            return $q;
        });

        return [
            "tareas" => $tareas,
        ];
    }
    function resolveTareaCentral(Request $req) {
        $id_tarea = $req->id_tarea;
        $re = tareasSucursales::find($id_tarea);
        if ($re->estado==0) {
            $re->estado = 1;
            if ($re->save()) {
                return ["estado" => true];
            }
        }else{
            return ["estado" => false];
        }
    }

    function sendTareaRemoverDuplicado(Request $req) {
        $listselectEliminarDuplicados = $req->listselectEliminarDuplicados;

        if (count($listselectEliminarDuplicados)>1) {
            $id_sucursals = [];
            $id_sucursal = null;
            foreach ($listselectEliminarDuplicados as $i => $e) {
                $i = inventario_sucursal::find($e["id"]);
                $id_sucursals[$i["id_sucursal"]] = null;
                $id_sucursal = $i["id_sucursal"];
            }
            if (count($id_sucursals)==1) {
                
                $new = new tareasSucursales;
                $new->id_sucursal = $id_sucursal; 
                $new->tipo = 2;
                $new->estado = 0;
                $new->antesproducto = null;
                $new->cambiarproducto = null;
                $new->idinsucursal = null;
                
                $rojo = "";
                foreach ($listselectEliminarDuplicados as $i => $e) {
                    if ($i!=0) {
                        $rojo .= $e["idinsucursal"].",";
                    }
                }
                $rojo = rtrim($rojo, ",");
                $new->id_producto_verde = $listselectEliminarDuplicados[0]["idinsucursal"];
                $new->id_producto_rojo = $rojo;
                if ($new->save()) {
                    return ["estado"=>true, "msj"=>"Éxito al registrar TAREA"];
                }


            }else{
                return ["msj"=>"Error: Hay productos de distintas sucursales","estado"=>false];

            }
        }else{
            return ["msj"=>"Error: Deben ser más de una Seleccion","estado"=>false];
        }
    }
}
