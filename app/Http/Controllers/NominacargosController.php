<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\nominacargos;
use Response;
class NominacargosController extends Controller
{
    public function delPersonalCargos(Request $req) {
        try{
            $id = $req->id;
            
            $setCargo = nominacargos::find($id)->delete();
            if ($setCargo) {
                return Response::json([
                    "msj" => "Éxito",
                    "estado" => true,
                ]);
            }
        }catch(\Exception $e){
            return Response::json([
                "msj" => "Error: ".$e->getMessage(),
                "estado" => false,
            ]);
        }
    }
    public function getPersonalCargos(Request $req) {
        $qCargos = $req->qCargos;
        return nominacargos::where(function($q) use ($qCargos){
            $q
            ->orWhere("cargosdescripcion", "LIKE", "$qCargos%")
            ->orWhere("cargossueldo", "LIKE", "$qCargos%");
        })
        ->orderBy("cargosdescripcion","asc")
        ->get();
    }

    public function setPersonalCargos(Request $req) {
        
        try{
            $cargosDescripcion = $req->cargosDescripcion;
            $cargosSueldo = $req->cargosSueldo;
            $id = $req->id;
            
            $setCargo = $this->setCargo([
                "cargosdescripcion" => $cargosDescripcion,
                "cargossueldo" => $cargosSueldo,
                "id" => $id,
            ]);
            if ($setCargo) {
                return Response::json([
                    "msj" => "Éxito",
                    "estado" => true,
                ]);
            }
        }catch(\Exception $e){
            return Response::json([
                "msj" => "Error: ".$e->getMessage(),
                "estado" => false,
            ]);
        }
    }

    function setCargo($arr) {
        return nominacargos::updateOrCreate([
            "id" => $arr["id"]
        ],[
            "cargosdescripcion" => $arr["cargosdescripcion"],
            "cargossueldo" => $arr["cargossueldo"],
        ]);
    }
}
