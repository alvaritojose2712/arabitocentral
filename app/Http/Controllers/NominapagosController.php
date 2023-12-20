<?php

namespace App\Http\Controllers;

use App\Models\nomina;
use Illuminate\Http\Request;

use App\Models\nominapagos;
use App\Http\Requests\StorenominapagosRequest;
use App\Http\Requests\UpdatenominapagosRequest;
use Response;
class NominapagosController extends Controller
{
    function setPagoNomina($ci, $monto, $id_sucursal, $idinsucursal) {

        $id_nomina = nomina::where("nominacedula",$ci)->first("id");
        if ($id_nomina) {
            $id_nomina = $id_nomina->id;

            return nominapagos::updateOrCreate([],[
                "monto" => $monto,
                "descripcion" => "PAGO NOMINA",
                "id_nomina" => $id_nomina,
                "id_sucursal" => $id_sucursal,
                "idinsucursal" => $idinsucursal,
            ]);
        }
    }
}
