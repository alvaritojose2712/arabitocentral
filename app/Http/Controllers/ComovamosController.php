<?php

namespace App\Http\Controllers;

use App\Models\comovamos;
use App\Http\Requests\StorecomovamosRequest;
use App\Http\Requests\UpdatecomovamosRequest;

use Illuminate\Http\Request;
use Response;


class ComovamosController extends Controller
{
    function setComovamos(Request $req) {
        try {
            $comovamos = $req->comovamos;

            $codigo_origen = $req->codigo_origen;
            $fecha = $req->fecha;
            
            $bs = $req->bs;
            $cop = $req->cop;
            
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];
    
            $transferencia = $comovamos["1"];
            $debito = $comovamos["2"];
            $efectivo = $comovamos["3"];
            $biopago = $comovamos["5"];
            $tasa = $bs;
            $tasa_cop = $cop;
            
            $numventas = $comovamos["numventas"];
            $total_inventario = $comovamos["total_inventario"];
            $total_inventario_base = $comovamos["total_inventario_base"];
            $cred_total = $comovamos["cred_total"];
            $total = $comovamos["total"];
            $precio = $comovamos["precio"];
            $precio_base = $comovamos["precio_base"];
            $desc_total = $comovamos["desc_total"];
            $ganancia = $comovamos["ganancia"];
            $porcentaje = $comovamos["porcentaje"];
    
            $obj = comovamos::updateOrCreate([
                "id_sucursal" => $id_sucursal,
                "fecha" => $fecha,
            ],[
                "transferencia" => $transferencia,
                "biopago" => $biopago,
                "debito" => $debito,
                "efectivo" => $efectivo,
                "tasa" => $tasa,
                "tasa_cop" => $tasa_cop,
                "numventas" => $numventas,
                "total_inventario" => $total_inventario,
                "total_inventario_base" => $total_inventario_base,
                "cred_total" => $cred_total,
                "total" => $total,
                "precio" => $precio,
                "precio_base" => $precio_base,
                "desc_total" => $desc_total,
                "ganancia" => $ganancia,
                "porcentaje" => $porcentaje,

                "id_sucursal" => $id_sucursal,
                "fecha" => $fecha,
            ]);
    
            if ($obj) {
                return "Listo, pana mio";
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
