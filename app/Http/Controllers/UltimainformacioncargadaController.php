<?php

namespace App\Http\Controllers;

use App\Models\ultimainformacioncargada;
use App\Http\Requests\StoreultimainformacioncargadaRequest;
use App\Http\Requests\UpdateultimainformacioncargadaRequest;
use Illuminate\Http\Request;
class UltimainformacioncargadaController extends Controller
{
    function getLast(Request $req) {
        $today = (new NominaController)->today();

        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];  

        return ultimainformacioncargada::where("id_sucursal",$id_sucursal)
        ->where("fecha","<>",$today)
        ->orderBy("id","desc")
        ->first();
    }
}
