<?php

namespace App\Http\Controllers;

use App\Models\bancos_list;
use App\Models\puntosybiopagos;
use App\Models\sucursal;
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

    function reverserLiquidar(Request $req) {
        $id = $req->id;
        $p = puntosybiopagos::find($id);
        $p->fecha_liquidacion = null;
        $p->monto_liquidado = 0;
        $p->save() ;
    }

    function liquidarMov(Request $req) {
        $id = $req->id;
        $fecha = $req->fecha;
        $monto = $req->monto;
        $p = puntosybiopagos::find($id);
        $p->fecha_liquidacion = $fecha;
        $p->monto_liquidado = $monto;
        if ($p->save()) {
            return [
                "estado" => true,
                "msj" => "Éxito al Liquidar",
            ];
        }


    }
    
    function sendMovimientoBanco(Request $req) {
        try {
            $id = null;
            $cuentasPagoTipo = $req->cuentasPagoTipo;
            $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
            
            $cuentasPagosMonto = $cuentasPagoTipo=="egreso"? $req->cuentasPagosMonto*-1:$req->cuentasPagosMonto;
            $cuentasPagosMetodo = $req->cuentasPagosMetodo;
            $cuentasPagosFecha = $req->cuentasPagosFecha;

            $cuentasPagosCategoria = $req->cuentasPagosCategoria;
    
            $today = new \DateTime((new NominaController)->today());
            $su = sucursal::updateOrCreate(["codigo"=>"administracion"],[
                "nombre" => "ADMINISTRACION",
                "codigo" => "administracion",
            ]);
            $banco = bancos_list::find($cuentasPagosMetodo);
            if ($banco) {
                $mov = puntosybiopagos::updateOrCreate([
                    "id" => $id
                ],[
                    "loteserial" => $cuentasPagosDescripcion,
                    "banco" => $banco->codigo,
                    "tipo" => "Transferencia",
                    "fecha" => $today,
                    "fecha_liquidacion" => $cuentasPagosFecha,
                    "monto" => $cuentasPagosMonto,
                    "monto_liquidado" => $cuentasPagosMonto,
                    "id_sucursal" => $su->id,
                    "id_usuario" => 1,
                    "categoria" => $cuentasPagosCategoria
                ]);
        
                if ($mov) {
                    return [
                        "estado" => true,
                        "msj" => "Éxito"
                    ];
                }
            }else{
                return [
                    "estado" => false,
                    "msj" => "No se encontró banco seleccionado",
                ];    
            }
    
        } catch (\Exception $e) {
            return [
                "estado" => false,
                "msj" => $e->getMessage()
            ];
        }

    }
}
