<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar_fisicas;
use App\Http\Requests\Storecuentasporpagar_fisicasRequest;
use App\Http\Requests\Updatecuentasporpagar_fisicasRequest;
use App\Models\sucursal;
use App\Models\usuarios;
use Illuminate\Http\Request;
use Response;


class CuentasporpagarFisicasController extends Controller
{
    function delFilescxp(Request $req) {
        $id = $req->id;
        $del = cuentasporpagar_fisicas::find($id);

        if ($del) {
            if ($del->estado==0) {
                $del->delete();
            }else{
                return "No se puede eliminar. Cuenta en Proceso...";
            }
        }
    }
    function getFilescxp(Request $req) {
        $qnumfactFilescxp = $req->qnumfactFilescxp;
        $qid_proveedorFilescxp = $req->qid_proveedorFilescxp;
        $qid_sucursalFilescxp = $req->qid_sucursalFilescxp;
        $qfechaFilescxp = $req->qfechaFilescxp;
        
        $cuentasporpagar_fisicas = cuentasporpagar_fisicas::with(["proveedor","sucursal"])
        ->when($qnumfactFilescxp, function($q) use ($qnumfactFilescxp){
            $q->where("numfact","LIKE","%".$qnumfactFilescxp."%");
        })
        ->when($qid_proveedorFilescxp, function($q) use ($qid_proveedorFilescxp){
            $q->where("id_proveedor",$qid_proveedorFilescxp);
        })
        ->when($qid_sucursalFilescxp, function($q) use ($qid_sucursalFilescxp){
            $q->where("id_sucursal",$qid_sucursalFilescxp);
        })
        ->when($qfechaFilescxp, function($q) use ($qfechaFilescxp){
            $q->where("created_at","LIKE",$qfechaFilescxp."%");
        })
        ->orderBy("estado","asc")
        ->get();

        return [
            "estado" => true, 
            "cuentasporpagar_fisicas" => $cuentasporpagar_fisicas,
        ];

    }
    function showFilescxp(Request $req) {
        
    }
    function sendComprasFats(Request $req){
        $fecha = date("dmY");
        $id_proveedor = $req->id_proveedor;
        $numfact = $req->numfact;
        $imagen = $req->imagen;

        $id_usuario = session("id_usuario");

        if ($id_usuario) {
            $usuario = usuarios::find($id_usuario);
            $findSucursal = sucursal::find($usuario->id_sucursal);
            if ($findSucursal) {
                $codigo_sucursal = $findSucursal->codigo;
                $filename = $fecha."-".$id_proveedor."-$numfact." . $imagen->getClientOriginalExtension();
                $carpeta = $id_proveedor."/".$codigo_sucursal;
                $imagen->move(public_path($carpeta), $filename);
                $path = $carpeta."/".$filename;
                $cuentasporpagar_fisicas = cuentasporpagar_fisicas::updateOrCreate([
                    "ruta" => $path,
                    "id_proveedor" => $id_proveedor,
                    "id_sucursal" => $usuario->id_sucursal,
                    "numfact" => $numfact,
                ],[

                    "estado" => 0,
                    "ruta" => $path,
                    "id_proveedor" => $id_proveedor,
                    "id_sucursal" => $usuario->id_sucursal,
                    "numfact" => $numfact,
                ]);
                if ($cuentasporpagar_fisicas->save()) {
                    
                    return "LISTO, PANA MÍO";
                }
        
            }else{
                return "Su usuario no tiene una sucursal asociada";
            }
        }
    }
}
