<?php

namespace App\Http\Controllers;

use App\Models\vinculomaestro;
use App\Http\Requests\StorevinculomaestroRequest;
use App\Http\Requests\UpdatevinculomaestroRequest;

class VinculomaestroController extends Controller
{
    function sendVinculoCentralToMaestro(Request $req) {
        $idinsucursal = $req->idinsucursal;
        $id_sucursal = $req->id_sucursal;
        $id_producto_central = $req->id_producto_central;

       
        $v = vinculomaestro::updateOrCreate([
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC

        ],[
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC
            
            "idinsucursal" => ($last_id?$last_id->id:0)+1, // INSUCURSAl, SOLO REF
        ]);



        $v = vinculomaestro::updateOrCreate([
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_producto_maestro" => $id_producto_central, //SUC

        ],[
        ]);


        if ($v) {
            return ["estado"=>1,"msj"=>"Éxito al Vincular"];
        }
    }
}
