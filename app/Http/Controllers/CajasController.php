<?php

namespace App\Http\Controllers;

use App\Models\cajas;
use App\Http\Requests\StorecajasRequest;
use App\Http\Requests\UpdatecajasRequest;
use App\Models\catcajas;
use App\Models\cuentasporpagar;
use App\Models\proveedores;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Response;
class CajasController extends Controller
{

    function getDisponibleEfectivoSucursal() {
        $arr = [];
        $dolarbalance = 0;
        $bsbalance = 0;
        $pesobalance = 0;
        $eurobalance = 0;
        $su = sucursal::orderByRaw("FIELD(id,1,2,5,4,3,6,7,15,8,9,10,11,16,12,14)")->get();

        foreach ($su as $sucursal) {
            $c = cajas::with("sucursal")->where("id_sucursal",$sucursal->id)->where("concepto","LIKE","%INGRESO DESDE CIERRE%")->orderBy("fecha","desc")->first();
            if ($c) {
                array_push($arr, $c);
                $dolarbalance += $c->dolarbalance;
                $bsbalance += $c->bsbalance;
                $pesobalance += $c->pesobalance;
                $eurobalance += $c->eurobalance;
            }
        }
        //array_multisort(array_column($arr,"id_sucursal"), SORT_ASC, $arr);
        return [
            "data" => $arr,
            "dolarbalance" => $dolarbalance,
            "bsbalance" => $bsbalance,
            "pesobalance" => $pesobalance,
            "eurobalance" => $eurobalance,
        ];
    }
    function setEfecFromSucursalToCentral($movs, $id_sucursal) {

        
            $count_movs = count($movs);
            $counter =0;
            $last = 0;
            if ($count_movs) {
                foreach ($movs as $e) {
                    if ($last<$e["id"]) {
                        $last=$e["id"];
                    }
    
                    $catnombre = $e["cat"]["nombre"];
                    $cattipo = $e["cat"]["tipo"];
                    $catindice = $e["cat"]["id"];
                    $checkcatcajas = catcajas::where("nombre",$catnombre)->where("tipo",$cattipo)->first();
                    if ($checkcatcajas) {
                        $setcategoria = $checkcatcajas->id;
                    }else{
                        $setcategoria = $catindice; 
                    }
    
                    if (strpos($catnombre,"NOMINA QUINCENA")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[1])) {
                            $ci = $split[1];
                            $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:$e["montopeso"]);
                            (new NominapagosController)->setPagoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                        }
                    }

                    if (strpos($catnombre,"NOMINA ABONO") || strpos($catnombre,"NOMINA PRESTAMO")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[1])) {
                            $ci = $split[1];
                            $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:$e["montopeso"]);

                            if (strpos($catnombre,"NOMINA ABONO")) {
                                $monto = abs($monto);
                            }
                            (new NominaprestamosController)->setPrestamoNomina($ci, $monto, $id_sucursal, $e["id"],$e["fecha"]);
                        }
                    }
                    
                    if (strpos($catnombre,"PAGO PROVEEDOR")) {
                        $split = explode("=",$e["concepto"]);
                        if (isset($split[2])) {
                            $id_proveedor_caja = $split[2];
    
                            $pro = proveedores::find($id_proveedor_caja);
                            if ($pro) {
                                $monto = $e["montodolar"]?$e["montodolar"]:($e["montobs"]?$e["montobs"]:($e["montopeso"]?$e["montoeuro"]:0));
                                $monto = $monto*-1;
                                $idinsucursal_pago = "PAGO_".$id_proveedor_caja."_".$e["id"];
                                $fecha_creada = date("Y-m-d", strtotime($e["created_at"]));
                                $numfact_desc = "PAGO ".$pro->descripcion." ".$fecha_creada;
    
                                (new CuentasporpagarController)->setPago([
                                    "id_sucursal" => $id_sucursal,
                                    "idinsucursal_pago" => $idinsucursal_pago,
                                    "id_proveedor_caja" => $id_proveedor_caja,
                                    "numfact_desc" => $numfact_desc,
                                    "monto" => $monto,
                                    "fecha_creada" => $fecha_creada,
                                ]);
                            }
                        }
                    }
                    /* if (strpos($catnombre,"TODAS SUCURSALES")) {
                        $todas_sucursales = sucursal::where("codigo","<>","administracion")->get();
                        $divisor = $todas_sucursales->count(); 
                        foreach ($todas_sucursales as $key => $sucursal) {
                            $arr_insert = [
                                
                                "montodolar" => $e["montodolar"]/$divisor,
                                "montobs" => $e["montobs"]/$divisor,
                                "montopeso" => $e["montopeso"]/$divisor,
                                "montoeuro" => $e["montoeuro"]/$divisor,
                                
                                "dolarbalance" => $sucursal["id"]==$id_sucursal? $e["dolarbalance"]:0,
                                "bsbalance" => $sucursal["id"]==$id_sucursal? $e["bsbalance"]:0,
                                "pesobalance" => $sucursal["id"]==$id_sucursal? $e["pesobalance"]:0,
                                "eurobalance" => $sucursal["id"]==$id_sucursal? $e["eurobalance"]:0,
                                
                                "concepto" => $e["concepto"]." - FRACCION 1/".$divisor,
                                "categoria" => $setcategoria,
                                
                                "fecha" => $e["fecha"],
                                "tipo" => $e["tipo"],
                                "id_sucursal" => $sucursal["id"],
                                "idinsucursal" => $e["id"].$sucursal["id"],
                            ] ; 
                            $cc =  cajas::updateOrCreate([
                                "id_sucursal" => $sucursal["id"],
                                "idinsucursal" => $e["id"].$sucursal["id"],
                            ],$arr_insert);
                        }
                    }else{ */
                        $arr_insert = [
                            "montoeuro" => $e["montoeuro"],
                            "eurobalance" => $e["eurobalance"],
        
                            "concepto" => $e["concepto"],
                            "categoria" => $setcategoria,
                            "montodolar" => $e["montodolar"],
                            "montopeso" => $e["montopeso"],
                            "montobs" => $e["montobs"],
                            "dolarbalance" => $e["dolarbalance"],
                            "pesobalance" => $e["pesobalance"],
                            "bsbalance" => $e["bsbalance"],
                            "fecha" => $e["fecha"],
                            "tipo" => $e["tipo"],
                            "id_sucursal" => $id_sucursal,
                            "idinsucursal" => $e["id"],
                        ] ; 
                        $cc =  cajas::updateOrCreate([
                            "id_sucursal" => $id_sucursal,
                            "idinsucursal" => $e["id"],
                            
                        ],$arr_insert);
                    /* } */
    
                    if ($cc) {
                        $counter++;
                    }
                }
                return [
                    "msj" => "OK CAJAS ".$counter . " / ".$count_movs,
                    "last" => $last
                ];
            }else{
                return [
                    "msj" => "OK CAJAS ".$counter . " / ".$count_movs,
                    "last" => 0
                ];
            }
        
    }
}
