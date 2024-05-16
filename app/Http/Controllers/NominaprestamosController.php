<?php

namespace App\Http\Controllers;

use App\Models\nominaprestamos;
use App\Models\nomina;
use App\Http\Requests\StorenominaprestamosRequest;
use App\Http\Requests\UpdatenominaprestamosRequest;

class NominaprestamosController extends Controller
{
    function setPrestamoNomina($ci, $monto, $id_sucursal, $idinsucursal,$fecha="") {

        $id_nomina = nomina::where("nominacedula",$ci)->first("id");
        if ($id_nomina) {
            $id_nomina = $id_nomina->id;

            return nominaprestamos::updateOrCreate([
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
            ],[
                "monto" => $monto,
                "descripcion" => ($monto<0?"PRESTAMO":"ABONO")." NOMINA",
                "id_nomina" => $id_nomina,
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
                "created_at" => $fecha?$fecha:date("Y-m-d H:i:s"),
            ]);
        }
    }
}
