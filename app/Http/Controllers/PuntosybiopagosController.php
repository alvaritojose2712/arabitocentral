<?php

namespace App\Http\Controllers;

use App\Models\puntosybiopagos;
use App\Http\Requests\StorepuntosybiopagosRequest;
use App\Http\Requests\UpdatepuntosybiopagosRequest;
use Illuminate\Http\Request;


class PuntosybiopagosController extends Controller
{
    function changeLiquidacionPagoElec(Request $req) {
        $id = $req->id;
        $change = puntosybiopagos::find($id);

        $change->fecha_liquidacion = date("Y-m-d");

        if ($change->save()) {
            return true;
        }

    }
}
