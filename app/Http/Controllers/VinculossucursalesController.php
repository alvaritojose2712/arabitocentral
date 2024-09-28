<?php

namespace App\Http\Controllers;

use App\Models\vinculossucursales;
use App\Http\Requests\StorevinculossucursalesRequest;
use App\Http\Requests\UpdatevinculossucursalesRequest;
use Illuminate\Http\Request;
use Response;

class VinculossucursalesController extends Controller
{
    function sendVinculoCentralToSucursal(Request $req) {
        $idinsucursal = $req->idinsucursal;
        $id_sucursal = $req->id_sucursal;
        $id_producto_central = $req->id_producto_central;

        $last_id = vinculossucursales::orderBy("id","desc")->first();
        $v = vinculossucursales::updateOrCreate([
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC

        ],[
            "id_producto_local" => $id_producto_central, //PROD CENTRAL
            "id_sucursal" => 13, //CENTRAL
            "idinsucursal_fore" => $idinsucursal, //PROD SUC
            "id_sucursal_fore" => $id_sucursal, //SUC
            
            "idinsucursal" => ($last_id->id)+1, // INSUCURSAl, SOLO REF
        ]);
        if ($v) {
            return ["estado"=>1,"msj"=>"Éxito al Vincular"];
        }


    }
}
