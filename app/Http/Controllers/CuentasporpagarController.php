<?php

namespace App\Http\Controllers;

use App\Models\bancos_list;
use App\Models\catcajas;
use App\Models\cuentasporpagar;
use App\Http\Requests\StorecuentasporpagarRequest;
use App\Http\Requests\UpdatecuentasporpagarRequest;
use App\Models\cuentasporpagar_fisicas;
use App\Models\cuentasporpagar_items;
use App\Models\cuentasporpagar_pagos;
use App\Models\proveedores;
use App\Models\sucursal;
use App\Models\pedidos;
use App\Models\items_pedidos;
use App\Models\inventario_sucursal;
use App\Models\puntosybiopagos;
use App\Models\cajas;
use App\Models\vinculossucursales;
use App\Models\compras_notascreditodebito;






use Illuminate\Http\Request;
use Response;
use DB;


class CuentasporpagarController extends Controller
{

    function getBalance($id_proveedor,$cuentaporpagarAprobado){
        $b = cuentasporpagar::selectRaw("@monto_condescuento := SUM((1-(COALESCE(descuento,0)/100))*monto) AS monto_condescuento")
        ->where("id_proveedor", $id_proveedor)
        ->where("aprobado",$cuentaporpagarAprobado)
        ->first("monto_condescuento");
        if ($b) {
            return $b->monto_condescuento;
        }
        return 0;
    }

    function getVencido($id_proveedor,$cuentaporpagarAprobado) {
        $today = (new NominaController)->today();

        $b = cuentasporpagar::selectRaw("@monto_condescuento := SUM((1-(COALESCE(descuento,0)/100))*monto) AS monto_condescuento")
        ->where("id_proveedor", $id_proveedor)
        ->where("aprobado",$cuentaporpagarAprobado)

        ->where("fechavencimiento","<=",$today)
        ->where("estatus",0)
        ->where("monto","<=",0)
        ->first("monto_condescuento");
        
        if ($b) {
            return $b->monto_condescuento;
        }
        return 0;
    }
    function getporVencer($id_proveedor,$cuentaporpagarAprobado) {
        $today = (new NominaController)->today();

        $b = cuentasporpagar::selectRaw("@monto_condescuento := SUM((1-(COALESCE(descuento,0)/100))*monto) AS monto_condescuento")
        ->where("id_proveedor", $id_proveedor)
        ->where("aprobado",$cuentaporpagarAprobado)

        ->where("fechavencimiento",">",$today)
        ->where("estatus",0)
        ->where("monto","<=",0)
        
        ->first("monto_condescuento");
        if ($b) {
            return $b->monto_condescuento;
        }
        return 0;
    }
    function sendPagoCuentaPorPagar(Request $req) {
        $cuentasPagosDescripcion = $req->cuentasPagosDescripcion;
        $cuentasPagosMonto = $req->cuentasPagosMonto;
        $cuentasPagosMetodo = $req->cuentasPagosMetodo;
        $cuentasPagosFecha = $req->cuentasPagosFecha;
        $id_pro = $req->id_pro;
        $selectAbonoFact = $req->selectAbonoFact;

        $montobs1PagoFact = $req->montobs1PagoFact;
        $tasabs1PagoFact = $req->tasabs1PagoFact;
        $metodobs1PagoFact = $req->metodobs1PagoFact;
        $refbs1PagoFact = $req->refbs1PagoFact;
        $montobs2PagoFact = $req->montobs2PagoFact;
        $tasabs2PagoFact = $req->tasabs2PagoFact;
        $metodobs2PagoFact = $req->metodobs2PagoFact;
        $refbs2PagoFact = $req->refbs2PagoFact;
        $montobs3PagoFact = $req->montobs3PagoFact;
        $tasabs3PagoFact = $req->tasabs3PagoFact;
        $metodobs3PagoFact = $req->metodobs3PagoFact;
        $refbs3PagoFact = $req->refbs3PagoFact;
        $montobs4PagoFact = $req->montobs4PagoFact;
        $tasabs4PagoFact = $req->tasabs4PagoFact;
        $metodobs4PagoFact = $req->metodobs4PagoFact;
        $refbs4PagoFact = $req->refbs4PagoFact;
        $montobs5PagoFact = $req->montobs5PagoFact;
        $tasabs5PagoFact = $req->tasabs5PagoFact;
        $metodobs5PagoFact = $req->metodobs5PagoFact;
        $refbs5PagoFact = $req->refbs5PagoFact;

        if ($cuentasPagosMetodo=="BANCO") {
            if (
                !$montobs1PagoFact
                && !$montobs2PagoFact
                && !$montobs3PagoFact
                && !$montobs4PagoFact
                && !$montobs5PagoFact
                ) {
                return ["estado"=>false,"msj"=>"Campos vacíos!"];
            }

            if (
                !$metodobs1PagoFact
                && !$metodobs2PagoFact
                && !$metodobs3PagoFact
                && !$metodobs4PagoFact
                && !$metodobs5PagoFact
                ) {
                return ["estado"=>false,"msj"=>"Campos vacíos!"];
            }

            if (
                !$refbs1PagoFact
                && !$refbs2PagoFact
                && !$refbs3PagoFact
                && !$refbs4PagoFact
                && !$refbs5PagoFact
                ) {
                return ["estado"=>false,"msj"=>"Campos vacíos!"];
            }
        }

        if ($montobs1PagoFact && $montobs1PagoFact!="0" && $montobs1PagoFact!="0.00") {
            if (!$tasabs1PagoFact || !$metodobs1PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 1"];
            }
        }
        if ($montobs2PagoFact && $montobs2PagoFact!="0" && $montobs2PagoFact!="0.00") {
            if (!$tasabs2PagoFact || !$metodobs2PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 2"];
            }
        }
        if ($montobs3PagoFact && $montobs3PagoFact!="0" && $montobs3PagoFact!="0.00") {
            if (!$tasabs3PagoFact || !$metodobs3PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 3"];
            }
        }
        if ($montobs4PagoFact && $montobs4PagoFact!="0" && $montobs4PagoFact!="0.00") {
            if (!$tasabs4PagoFact || !$metodobs4PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 4"];
            }
        }
        if ($montobs5PagoFact && $montobs5PagoFact!="0" && $montobs5PagoFact!="0.00") {
            if (!$tasabs5PagoFact || !$metodobs5PagoFact) {
                return ["estado"=>false ,"msj"=>"Campo Vacío en Pago en montobs 5"];
            }
        }

        if (!$cuentasPagosMetodo||$cuentasPagosMetodo=="BANCO") {
            if (
                $montobs1PagoFact ||
                $montobs2PagoFact ||
                $montobs3PagoFact ||
                $montobs4PagoFact ||
                $montobs5PagoFact

            ) {
                $cuentasPagosMetodo = "Transferencia";
            }
        }
        if ($selectAbonoFact) {
            foreach ($selectAbonoFact as $abonoFact) {
                $c = cuentasporpagar::with("proveedor")->find($abonoFact["id"]);
                if ($c) {
                    if ($c->proveedor->id!=$id_pro) {
                        return ["estado"=>false,"msj"=>$c->numfact." no es del Proveedor Seleccionado en el Abono"];
                    }
                }
            }
        }

        $id = isset($req->id)? $req->id: null;
        $id_sucursal = isset($req->id_sucursal)? $req->id_sucursal: null;

        if (
            !$cuentasPagosDescripcion || 
            !$cuentasPagosMonto || 
            !$cuentasPagosFecha 
        ) {
            return [
                "estado" => false,
                "msj" => "Error: Campos vacíos",
                "id_proveedor" => $id_pro,
                "cuentasPagosDescripcion" => $cuentasPagosDescripcion,
                "cuentasPagosMonto" => $cuentasPagosMonto,
                "cuentasPagosFecha" => $cuentasPagosFecha,
            ];
        }
        
        $admin_id = 13;
        $pago = $this->setPago([
            "id_sucursal" => $id_sucursal? $id_sucursal: $admin_id,
            "idinsucursal_pago" => $id?$id:time(),

            "id_proveedor_caja" => $id_pro,
            "numfact_desc" => $cuentasPagosDescripcion,
            "monto" => $cuentasPagosMonto,
            "fecha_creada" => $cuentasPagosFecha,
            "metodo" => $cuentasPagosMetodo,
            "selectAbonoFact" =>$selectAbonoFact,
            "aprobado" =>1,

            "montobs1PagoFact" => $montobs1PagoFact,
            "tasabs1PagoFact" => $tasabs1PagoFact,
            "metodobs1PagoFact" => $metodobs1PagoFact,
            "montobs2PagoFact" => $montobs2PagoFact,
            "tasabs2PagoFact" => $tasabs2PagoFact,
            "metodobs2PagoFact" => $metodobs2PagoFact,
            "montobs3PagoFact" => $montobs3PagoFact,
            "tasabs3PagoFact" => $tasabs3PagoFact,
            "metodobs3PagoFact" => $metodobs3PagoFact,
            "montobs4PagoFact" => $montobs4PagoFact,
            "tasabs4PagoFact" => $tasabs4PagoFact,
            "metodobs4PagoFact" => $metodobs4PagoFact,
            "montobs5PagoFact" => $montobs5PagoFact,
            "tasabs5PagoFact" => $tasabs5PagoFact,
            "metodobs5PagoFact" => $metodobs5PagoFact,

            "refbs1PagoFact" => $refbs1PagoFact,
            "refbs2PagoFact" => $refbs2PagoFact,
            "refbs3PagoFact" => $refbs3PagoFact,
            "refbs4PagoFact" => $refbs4PagoFact,
            "refbs5PagoFact" => $refbs5PagoFact,
        ]);
        if ($pago) {
            return [
                "estado" => true,
                "msj" => "Pago registrado con éxito",
                "id_proveedor" => $id_pro
            ];
        }
        
    }


    function setCuentaPorPagar($arr,$search) {

        return cuentasporpagar::updateOrCreate($search,$arr);
    }
    function negative($num){
        return -1 * abs(floatval($num));
    }

    function conciliarCuenta(Request $req) {
        $id = $req->id;

        $c = cuentasporpagar::find($id);
        if ($c) {
            if ($c->conciliada==0) {
                $c->conciliada = 1;
            }else{
                $c->conciliada = 0;
            }
            $c->save();
            return Response::json(["msj"=>"Conciliado", "estado"=>true]);
        }
    }
    function saveFacturaLote(Request $req){

        try {
            $facturas =   array_values(json_decode($req->facturas, true));
            $selectFilecxp = $req->selectFilecxp;
            $imagenreq = $req->imagen;
            $fileId = null;
            
            $msj = "";
            foreach ($facturas as $i => $factura) {

                $ifexistfact = cuentasporpagar::find($factura["id"]);

                if ($ifexistfact) {
                    if ($ifexistfact->aprobado==1) {
                        return "No puede modificar una factura aprobada ".$ifexistfact->numfact;
                    }
                }
                if (isset($factura["type"])) {
                    $type = $factura["type"];
                    if ($type=="update" || $type=="new") {
                        $arrinsert = [
                            "tipo" => 1, //COMPRAS
                            "frecuencia" => 0,
                            "idinsucursal" => null,
                            "id_proveedor" =>  $factura["id_proveedor"],
                            "id_sucursal" =>  $factura["id_sucursal"],
                            "numfact" => $factura["numfact"],
                            "numnota" => $factura["numnota"],
                            
                            "descuento" => $factura["descuento"],
                            "subtotal" => $this->negative($factura["subtotal"]),
                            "monto_exento" => $this->negative($factura["monto_exento"]),
                            "monto_gravable" => $this->negative($factura["monto_gravable"]),
                            "iva" => $this->negative($factura["iva"]),
                            "monto" => $this->negative($factura["monto"]),
                            "fechaemision" => $factura["fechaemision"],
                            "fechavencimiento" => $factura["fechavencimiento"],
                            "fecharecepcion" => $factura["fecharecepcion"],
                            "nota" => $factura["nota"],
                        ];
                        
                        if ($imagenreq) {
                            $fileId = (new CuentasporpagarFisicasController)->sendComprasFatsFun([
                                "id_proveedor" => $factura["id_proveedor"],
                                "id_sucursal" => $factura["id_sucursal"],
                                "numfact" => $factura["numfact"],
                                "imagen" => $imagenreq,
                            ]);
                        }

                        if (isset($fileId["id"]) && $imagenreq) {
                            $selectFilecxp = $fileId["id"];
                        }
                        if ($selectFilecxp) {
                            $fisica = cuentasporpagar_fisicas::find($selectFilecxp);
                            if ($fisica) {
                                $arrinsert["descripcion"] = $fisica->ruta;
                            }
                        }
                        
                        
                        $search = [
                            "id" => $factura["id"]
                        ];
                        $this->setCuentaPorPagar($arrinsert,$search);
                        
                    }else if($type=="delete"){
                        //cuentasporpagar::find($factura["id"])->delete();
                    }
                    $msj .= ($i+1)." ".$type;
                }
    
            }
            return ["msj" => $msj, "estado"=>true];
        } catch (\Exception $e) {
            return ["estado"=>false, "msj"=>$e->getMessage()];
        }

    }
    function setPago($arr) {

        $id_sucursal = $arr["id_sucursal"];
        $idinsucursal_pago = $arr["idinsucursal_pago"];
        $id_proveedor_caja = $arr["id_proveedor_caja"];
        $numfact_desc = $arr["numfact_desc"];
        $monto = $arr["monto"];
        $fecha_creada = $arr["fecha_creada"];
        $metodo = isset($arr["metodo"])?$arr["metodo"]:null;
        $aprobado = isset($arr["aprobado"])?$arr["aprobado"]:0;

        $montobs1PagoFact = isset($arr["montobs1PagoFact"])? $arr["montobs1PagoFact"]: 0;
        $tasabs1PagoFact = isset($arr["tasabs1PagoFact"])? $arr["tasabs1PagoFact"]: 0;
        $montobs2PagoFact = isset($arr["montobs2PagoFact"])? $arr["montobs2PagoFact"]: 0;
        $tasabs2PagoFact = isset($arr["tasabs2PagoFact"])? $arr["tasabs2PagoFact"]: 0;
        $montobs3PagoFact = isset($arr["montobs3PagoFact"])? $arr["montobs3PagoFact"]: 0;
        $tasabs3PagoFact = isset($arr["tasabs3PagoFact"])? $arr["tasabs3PagoFact"]: 0;
        $montobs4PagoFact = isset($arr["montobs4PagoFact"])? $arr["montobs4PagoFact"]: 0;
        $tasabs4PagoFact = isset($arr["tasabs4PagoFact"])? $arr["tasabs4PagoFact"]: 0;
        $montobs5PagoFact = isset($arr["montobs5PagoFact"])? $arr["montobs5PagoFact"]: 0;
        $tasabs5PagoFact = isset($arr["tasabs5PagoFact"])? $arr["tasabs5PagoFact"]: 0;
        
        
        $metodobs1PagoFact = isset($arr["metodobs1PagoFact"])? $arr["metodobs1PagoFact"]: 0;
        $metodobs2PagoFact = isset($arr["metodobs2PagoFact"])? $arr["metodobs2PagoFact"]: 0;
        $metodobs3PagoFact = isset($arr["metodobs3PagoFact"])? $arr["metodobs3PagoFact"]: 0;
        $metodobs4PagoFact = isset($arr["metodobs4PagoFact"])? $arr["metodobs4PagoFact"]: 0;
        $metodobs5PagoFact = isset($arr["metodobs5PagoFact"])? $arr["metodobs5PagoFact"]: 0;

        $refbs1PagoFact = isset($arr["refbs1PagoFact"])? $arr["refbs1PagoFact"]: 0;
        $refbs2PagoFact = isset($arr["refbs2PagoFact"])? $arr["refbs2PagoFact"]: 0;
        $refbs3PagoFact = isset($arr["refbs3PagoFact"])? $arr["refbs3PagoFact"]: 0;
        $refbs4PagoFact = isset($arr["refbs4PagoFact"])? $arr["refbs4PagoFact"]: 0;
        $refbs5PagoFact = isset($arr["refbs5PagoFact"])? $arr["refbs5PagoFact"]: 0;
        
        
        $selectAbonoFact = isset($arr["selectAbonoFact"])?$arr["selectAbonoFact"]:null;

        $sql_metodobs1PagoFact = null;
        $sql_metodobs2PagoFact = null;
        $sql_metodobs3PagoFact = null;
        $sql_metodobs4PagoFact = null;
        $sql_metodobs5PagoFact = null;

        if ($metodobs1PagoFact) {
            $sql_metodobs1PagoFact = bancos_list::find($metodobs1PagoFact);
        }
        if ($metodobs2PagoFact) {
            $sql_metodobs2PagoFact = bancos_list::find($metodobs2PagoFact);
        }
        if ($metodobs3PagoFact) {
            $sql_metodobs3PagoFact = bancos_list::find($metodobs3PagoFact);
        }
        if ($metodobs4PagoFact) {
            $sql_metodobs4PagoFact = bancos_list::find($metodobs4PagoFact);
        }
        if ($metodobs5PagoFact) {
            $sql_metodobs5PagoFact = bancos_list::find($metodobs5PagoFact);
        }


        $codigo_metodobs1PagoFact = $sql_metodobs1PagoFact? $sql_metodobs1PagoFact->codigo:null;
        $codigo_metodobs2PagoFact = $sql_metodobs2PagoFact? $sql_metodobs2PagoFact->codigo:null;
        $codigo_metodobs3PagoFact = $sql_metodobs3PagoFact? $sql_metodobs3PagoFact->codigo:null;
        $codigo_metodobs4PagoFact = $sql_metodobs4PagoFact? $sql_metodobs4PagoFact->codigo:null;
        $codigo_metodobs5PagoFact = $sql_metodobs5PagoFact? $sql_metodobs5PagoFact->codigo:null;

        $montobs1 = $montobs1PagoFact;
        $tasabs1 = $tasabs1PagoFact;
        $metodobs1 = $codigo_metodobs1PagoFact;
        $metodobs2 = $codigo_metodobs2PagoFact;
        $metodobs3 = $codigo_metodobs3PagoFact;
        $metodobs4 = $codigo_metodobs4PagoFact;
        $metodobs5 = $codigo_metodobs5PagoFact;
        $id_metodobs1 = $metodobs1PagoFact;
        $id_metodobs2 = $metodobs2PagoFact;
        $id_metodobs3 = $metodobs3PagoFact;
        $id_metodobs4 = $metodobs4PagoFact;
        $id_metodobs5 = $metodobs5PagoFact;
        $montobs2 = $montobs2PagoFact;
        $tasabs2 = $tasabs2PagoFact;
        $montobs3 = $montobs3PagoFact;
        $tasabs3 = $tasabs3PagoFact;
        $montobs4 = $montobs4PagoFact;
        $tasabs4 = $tasabs4PagoFact;
        $montobs5 = $montobs5PagoFact;
        $tasabs5 = $tasabs5PagoFact;
        $refbs1 = $refbs1PagoFact;
        $refbs2 = $refbs2PagoFact;
        $refbs3 = $refbs3PagoFact;
        $refbs4 = $refbs4PagoFact;
        $refbs5 = $refbs5PagoFact;

        $search = [
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $idinsucursal_pago
        ];

        $pagos_bancos = [
            [
                "montobs" => $montobs1,
                "tasabs" => $tasabs1,
                "metodobs" => $metodobs1,
                "id_metodobs" => $id_metodobs1,
                "refbs" => $refbs1,
            ],

            [
                "montobs" => $montobs2,
                "tasabs" => $tasabs2,
                "metodobs" => $metodobs2,
                "id_metodobs" => $id_metodobs2,
                "refbs" => $refbs2,
            ],

            [
                "montobs" => $montobs3,
                "tasabs" => $tasabs3,
                "metodobs" => $metodobs3,
                "id_metodobs" => $id_metodobs3,
                "refbs" => $refbs3,
            ],

            [
                "montobs" => $montobs4,
                "tasabs" => $tasabs4,
                "metodobs" => $metodobs4,
                "id_metodobs" => $id_metodobs4,
                "refbs" => $refbs4,
            ],

            [
                "montobs" => $montobs5,
                "tasabs" => $tasabs5,
                "metodobs" => $metodobs5,
                "id_metodobs" => $id_metodobs5,
                "refbs" => $refbs5,
            ],
        ];
        $search = [
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $idinsucursal_pago
        ];

        $arrinsert = [
            "id_proveedor" => $id_proveedor_caja,
            "tipo" => 1, //COMPRAS
            "frecuencia" => 0,
            "id_sucursal" => $id_sucursal,

            "idinsucursal" => $idinsucursal_pago,
            "numfact" => $numfact_desc,
            "numnota" => "",
            "descripcion" => $numfact_desc,

            "subtotal" => $monto,
            "monto" => $monto,
            "fechaemision" => $fecha_creada,
            "fechavencimiento" => $fecha_creada,
            "fecharecepcion" => $fecha_creada,
            "nota" => "",
            "metodo" => $metodo,
            "aprobado" => $aprobado,
        ];
        $cuenta = cuentasporpagar::updateOrCreate($search,$arrinsert);
        

        if ($metodo=="EFECTIVO") {
            cajas::where("id_cxp", $cuenta->id)->delete();

            $pago = (new CajasController)->setCajaFun([
                "id" => null,
                "categoria" => 40, //CAJA FUERTE: PAGO PROVEEDOR
                "tipo" => 1,
                "concepto" => $numfact_desc,

                "montodolar" => abs($monto)*-1,
                "montopeso" => 0,
                "montobs" => 0,
                "montoeuro" => 0,

                "fecha" => $fecha_creada,
                "id_proveedor" => $id_proveedor_caja,
                "id_cxp" => $cuenta->id,
                //"idinsucursal" => null,
            ]);
        }else{
            puntosybiopagos::where("id_cxp", $cuenta->id)->delete();
            foreach ($pagos_bancos as $i => $e) {
                if ($e["refbs"] && $e["metodobs"] && $e["id_metodobs"] && $e["montobs"] && $e["tasabs"]) {
                    $banco = puntosybiopagos::updateOrCreate([
                        "loteserial" => $numfact_desc." #".$e["refbs"],
                        "id_banco" => $e["id_metodobs"],
                        "fecha" => $fecha_creada,
                        "id_cxp" => $cuenta->id,
                    ],[
                        "loteserial" => $numfact_desc." #".$e["refbs"],
                        "banco" => $e["metodobs"],
                        "id_banco" => $e["id_metodobs"],
                        "categoria" => 40,
        
                        "fecha" => $fecha_creada,
                        "fecha_liquidacion" => $fecha_creada,
                        "tipo" => "Transferencia",
            
                        "id_sucursal" => 13,
                        "id_beneficiario" => null,
                        "tasa" => $e["tasabs"],
                        "monto_liquidado" => abs($e["montobs"])*-1,
                        "monto" => abs($e["montobs"])*-1,
                        "monto_dolar" => null,
                        "origen" => 2,
                        "id_usuario" => session("id_usuario")?session("id_usuario"):1,
                        "id_cxp" => $cuenta->id,
                    ]);
                }
            }
        }

        if ($selectAbonoFact) {
            if (count($selectAbonoFact)) {
                $msjAbono = "";
                cuentasporpagar_pagos::where("id_pago",$cuenta->id)->delete();
                foreach ($selectAbonoFact as $e) {
                    $update_cuenta = cuentasporpagar_pagos::updateOrCreate([
                        "id_factura" => $e["id"],
                        "id_pago" => $cuenta->id,
                    ],[
                        "id_factura" => $e["id"],
                        "id_pago" => $cuenta->id,
                        "monto" => $e["val"],
                        "tipo" => $metodo=="EFECTIVO" ? 2 : 1
                    ]);
                    //$table->integer("tipo")->nullable(); //1 BANCO //2 EFECTIVO
                    
                    $this->setEstatusFact($e["id"]);

                    
                    if ($update_cuenta) {
                        $msjAbono .= $e["val"]." | ";
                    }else{
                        $msjAbono .= "ERROR: " . $e["val"]." | ";
                    }
                    
                }
                return $msjAbono;
                
            }
        }
    }


    function removeDuplicatesCXP() {

        $du = cuentasporpagar::where("aprobado",0)->selectRaw("id_proveedor, numfact, COUNT(*) as count")->groupByRaw("id_proveedor, numfact")->havingRaw("COUNT(*) > 1")->get();

        foreach ($du as $key => $val) {
            $numfact = $val["numfact"]; 
            $id_proveedor = $val["id_proveedor"]; 
            $count = $val["count"]-1;

            //cuentasporpagar::where("numfact",$numfact)->where("id_proveedor",$id_proveedor)->limit($count)->delete();

            echo "$id_proveedor __ $numfact ____ $count veces <br>";
        }
    }
    function sendFacturaCentral(Request $req){
        try {
            
            $codigo_origen = $req->codigo_origen;
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];


            $factura = json_decode($req->factura,2);
            $imagen = $req->imagen;

            return [
                "msj" => "Ya no tienes permisos para subir facturas!",
                "idinsucursal" => null,
            ];

            

            if ($factura["proveedor"]) {
                $id_proveedor = proveedores::where("rif",$factura["proveedor"]["rif"])->get()->first();

                
                if ($id_proveedor) {
                    $factor = -1;
                    $arrinsert = [
                        "id_proveedor" => $id_proveedor->id,
                        "tipo" => 1, //COMPRAS
                        "frecuencia" => 0,
                        "id_sucursal" => $id_sucursal,

                        "idinsucursal" => $factura["id"],
                        "numfact" => $factura["numfact"],
                        "numnota" => $factura["numnota"],
                        "descripcion" => $factura["descripcion"],

                        "subtotal" => $factura["subtotal"]*$factor,
                        "descuento" => $factura["descuento"]*$factor,
                        "monto_exento" => $factura["monto_exento"]*$factor,
                        "monto_gravable" => $factura["monto_gravable"]*$factor,
                        "iva" => $factura["iva"]*$factor,
                        "monto" => $factura["monto"]*$factor,
                        
                        "fechaemision" => $factura["fechaemision"],
                        "fechavencimiento" => $factura["fechavencimiento"],
                        "fecharecepcion" => $factura["fecharecepcion"],
                        "nota" => $factura["nota"],
                    ];

                    $search = [
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $factura["id"]
                    ];
                    $registrarfactura = $this->setCuentaPorPagar($arrinsert,$search);
        
                    if ($registrarfactura) {
                        $filename = $registrarfactura->id . "." . $imagen->getClientOriginalExtension();
                        $imagen->move(public_path('facturas'), $filename);
                        $updateImage = cuentasporpagar::find($registrarfactura->id);
                        $updateImage->descripcion = $filename;
                        $updateImage->save();

                        return [
                            "msj" => "Desde Central: Éxito al registrar Factura ID_CENTRAL_FACT:".$registrarfactura->id,
                            "idinsucursal" => $factura["id"]
                        ];
                    }
        
                }
            }else{
                return "Error CuentasporpagarController sendFacturaCentral";
            }

        } catch (\Exception $e) {
            return $e->getMessage()." LINEA ".$e->getLine()." CuentasporpagarController sendFacturaCentral";
        }


    }
    function showImageFact() {
        
    }

    function getCuentas($fechasMain1, $fechasMain2, $id_sucursal, $filtros){
        $qcuentasPorPagar = $filtros["qcuentasPorPagar"];
        $totalSum = 0;

        $cuentasporpagar = proveedores::when($qcuentasPorPagar!="",function($q) use ($qcuentasPorPagar){
            $q->orWhere("descripcion","LIKE","%$qcuentasPorPagar%")
            ->orWhere("rif","LIKE","%$qcuentasPorPagar%");
        })
        ->get()
        ->map(function($q) use (&$totalSum){
            $b = $this->getBalance($q->id,1);
            $vencido = $this->getVencido($q->id,1);
            $porVencer = $this->getporVencer($q->id,1);
            $q->vencido = $vencido?$vencido:0; 
            $q->porVencer = $porVencer?$porVencer:0; 
            $q->balance = $b?$b:0; 

            $totalSum += $b;
            
            return $q; 
        })->toArray();

        $cuentasporpagarColumn = array_column($cuentasporpagar, 'vencido');
        array_multisort($cuentasporpagarColumn, SORT_ASC, $cuentasporpagar);
        
        return [
            "cuentasporpagar" => collect($cuentasporpagar),
            "sum" => $totalSum,
        ];
    }
    function changeSucursal(Request $req) {
        
        /* $su = $req->sucursal;        
        $sucursal = sucursal::where("codigo",$su)->first();
        if ($sucursal) {
            $upd = cuentasporpagar::find($req->id);
            $upd->id_sucursal = $sucursal->id;
            return $upd->save();
        }else{
            return "No se encontró Sucursal";
        } */
        
    }

    function saveNewFact(Request $req) {

        try {
            $newfactid_proveedor = $req->newfactid_proveedor;
            $newfactnumfact = $req->newfactnumfact;
            $newfactnumnota = $req->newfactnumnota;
            $newfactdescripcion = $req->newfactdescripcion;
            $newfactsubtotal = $req->newfactsubtotal;
            $newfactdescuento = $req->newfactdescuento;
            $newfactmonto_exento = $req->newfactmonto_exento;
            $newfactmonto_gravable = $req->newfactmonto_gravable;
            $newfactiva = $req->newfactiva;
            $newfactmonto = $req->newfactmonto;

            $newfactfechaemision = $req->newfactfechaemision;
            $newfactfechavencimiento = $req->newfactfechavencimiento;
            $newfactfecharecepcion = $req->newfactfecharecepcion;
            
            $newfactnota = $req->newfactnota;
            $newfacttipo = $req->newfacttipo;
            $newfactfrecuencia = $req->newfactfrecuencia;
    
            $id = $req->id;

            

            $factor = -1;
            if ($id) {
                $checkabonocred = cuentasporpagar::find($id);
                if ($checkabonocred) {
                    if ($checkabonocred->monto<0) {
                        $factor = -1;
                    }
                }
            }
            $arrinsert = [
                "numfact" => $newfactnumfact,
                "numnota" => $newfactnumnota,
                "descripcion" => $newfactdescripcion,
                
                "subtotal" => $newfactsubtotal*$factor,
                "descuento" => $newfactdescuento,
                "monto_exento" => $newfactmonto_exento*$factor,
                "monto_gravable" => $newfactmonto_gravable*$factor,
                "iva" => $newfactiva*$factor,
                "monto" => $newfactmonto*$factor,
                
                "nota" => $newfactnota,
                "tipo" => $newfacttipo,
                "frecuencia" => $newfactfrecuencia,
            ];
            
            $arrinsert["fechaemision"] = $newfactfechaemision;
            $arrinsert["fechavencimiento"] = $newfactfechavencimiento;
            $arrinsert["fecharecepcion"] = $newfactfecharecepcion;
            if (!$id) {
                $arrinsert["id_proveedor"] = $newfactid_proveedor;
                $arrinsert["id_sucursal"] = 13;
            }
            $search = ["id" => $id];
            $cu = $this->setCuentaPorPagar($arrinsert,$search);
            $this->setEstatusFact($cu->id);
            
            if ($cu) {
                return ["estado" => true, "msj"=>"Éxito al registar"];
            }
        } catch (\Exception $e) {
            return [
                "estado" => false,
                "msj" => $e->getMessage()
            ];
        }
        
    }
    function changeAprobarFact(Request $req) {
        $c = cuentasporpagar::find($req->id);
        if ($c->aprobado == 1) {
            $c->aprobado = 0;
        }else{
            $c->aprobado = 1;
        }
        $c->save();
    }
    function delCuentaPorPagar(Request $req) {
        try {
            $id = $req->id;
     
            $cuenta = cuentasporpagar::find($id);
            if ($cuenta->aprobado==1) {
                 return [
                     "estado"=> false,
                     "msj"=> "Factura aprobada. No se puede eliminar",
                 ];
            }else{
                 if ($cuenta->delete()) {
                     return [
                         "estado"=> true,
                         "msj"=> "Éxito al eliminar",
                     ];
                 }
            }
        } catch (\Exception $e) {
            return $e->getMessage()." LINEA ".$e->getLine()." CuentasporpagarController delCuentaPorPagar";

        }
    }
    function setEstatusFact($id){
        $cuenta = cuentasporpagar::find($id);
        $monto_abonado = cuentasporpagar_pagos::where("id_factura", $id)->sum("monto");

        $monto = $cuenta->monto ? $cuenta->monto: 0;
        $descuento = $cuenta->descuento ? $cuenta->descuento: 0;

        $monto_descuento = $monto*(($descuento/100)?$descuento/100:0);
        $balance = $monto_abonado + $monto - $monto_descuento;
        $estatus = 0;
        if ($balance > -0.1 && $balance < 0.1) {
            $estatus = 2;
        }elseif ($monto_abonado>0) {
            $estatus = 1;
        }

        $cuenta->estatus = $estatus;
        $cuenta->updated_at = date("Y-m-d H:i:s");
        $cuenta->save();
    }
    function setEstatusAll(){
     $cuentas= cuentasporpagar::all();
     
     foreach ($cuentas as $key => $value) {
        $this->setEstatusFact($value->id);
     }
    }

    function sendDescuentoGeneralFats(Request $req) {
        $dataselectFacts = $req->dataselectFacts;
        $descuentoGeneralFats = $req->descuentoGeneralFats;

        foreach ($dataselectFacts as $key => $e) {
            $c = cuentasporpagar::find($e["id"]);
            $c->descuento = $descuentoGeneralFats;
            $c->save();
        }
    }


    function selectCuentaPorPagarProveedorDetallesFun($arr) {
        $id_proveedor = $arr["id_proveedor"];
        $qcampoBusquedacuentasPorPagarDetalles = isset($arr["qcampoBusquedacuentasPorPagarDetalles"])?$arr["qcampoBusquedacuentasPorPagarDetalles"]:"numfact";
        $qinvertircuentasPorPagarDetalles = isset($arr["qinvertircuentasPorPagarDetalles"])?$arr["qinvertircuentasPorPagarDetalles"]:"0";
        $cuentaporpagarAprobado = $arr["cuentaporpagarAprobado"];
        $categoriacuentasPorPagarDetalles = $arr["categoriacuentasPorPagarDetalles"];
        $tipocuentasPorPagarDetalles = $arr["tipocuentasPorPagarDetalles"];
        $qcuentasPorPagarTipoFact = $arr["qcuentasPorPagarTipoFact"];
        $qCampocuentasPorPagarDetalles = $arr["qCampocuentasPorPagarDetalles"];
        $qcuentasPorPagarDetalles = $arr["qcuentasPorPagarDetalles"];
        $OrdercuentasPorPagarDetalles = $arr["OrdercuentasPorPagarDetalles"];
        $sucursalcuentasPorPagarDetalles = $arr["sucursalcuentasPorPagarDetalles"];
        $numcuentasPorPagarDetalles = $arr["numcuentasPorPagarDetalles"];
        $type = $arr["type"];
        $id_facts_force = $arr["id_facts_force"];


        $fechasMain1 = isset($arr["fechasMain1"])?$arr["fechasMain1"]:null;
        $fechasMain2 = isset($arr["fechasMain2"])?$arr["fechasMain2"]:null;

        $fasts_no = [];
        
        
        $todayWithoutDateTime = (new NominaController)->today();
        $today = new \DateTime($todayWithoutDateTime);
        $detalles = cuentasporpagar::with(["pedido","novedades","banco","efectivo","items"=>function($q){
            $q->with(["producto"=>function($q) {
                $q->with(["sucursal"]);
            }]);
        },"sucursal","proveedor","pagos"=>function($q) {
            
            $q->orderBy("id","desc");
        },"facturas"=>function($q) {
    
            $q->with(["sucursal","proveedor"])->orderBy("id","desc");
        }])
        ->selectRaw("*, @monto_abonado := ( SELECT sum(`cuentasporpagar_pagos`.`monto`) FROM cuentasporpagar_pagos WHERE `cuentasporpagar_pagos`.`id_factura` =`cuentasporpagars`.`id` ) as monto_abonado, 
        @monto_descuento := (COALESCE(monto,0)*(COALESCE(descuento,0)/100)) as monto_descuento,
        (COALESCE(@monto_abonado,0)+COALESCE(monto,0)-COALESCE(@monto_descuento,0)) as balance
        ")
        ->when($fechasMain1,function($q) use ($fechasMain1,$fechasMain2) {
            $q->whereBetween("fechaemision",[$fechasMain1,(!$fechasMain2?$fechasMain1:$fechasMain2)]);
        })
        ->when($numcuentasPorPagarDetalles,function($q) use ($numcuentasPorPagarDetalles) {
            $q->limit($numcuentasPorPagarDetalles);
        });
        if ($id_facts_force) {
            $detalles = $detalles->whereIn("id",$id_facts_force);
            
        }elseif(str_contains($qcuentasPorPagarDetalles,",")){
            $keys = explode(",",$qcuentasPorPagarDetalles);
    
            $detalles = $detalles
            ->when( ($id_proveedor != "" && $id_proveedor != null),function($q) use ($id_proveedor){
                $q->where("id_proveedor",$id_proveedor);
            }) 
            ->when($sucursalcuentasPorPagarDetalles!="",function($q) use ($sucursalcuentasPorPagarDetalles){
                $q->where("id_sucursal",$sucursalcuentasPorPagarDetalles);
            })
            ->when($categoriacuentasPorPagarDetalles!="",function($q) use ($categoriacuentasPorPagarDetalles) {
                $q->where("tipo","$categoriacuentasPorPagarDetalles");
            })
            ->when($tipocuentasPorPagarDetalles!="",function($q) use ($tipocuentasPorPagarDetalles) {
                if ($tipocuentasPorPagarDetalles=="DEUDA") {
                    $q->where("monto","<=",0);
                }else{
                    $q->where("monto",">",0);
                }
            })
            ->when($qcuentasPorPagarTipoFact=="",function($q) {
                $q->where("monto","<=",0)
                ->where("estatus","<>",2);
            })
            ->when($qcuentasPorPagarTipoFact!="",function($q) use ($qcuentasPorPagarTipoFact,$today){
                switch ($qcuentasPorPagarTipoFact) {
                    case "abonos":
                        $q->where("monto",">",0);
                    break;
                    case "pagadas":
                        $q->where("monto", "<=", "0")
                        ->where("estatus",2);
                        break;
                    case "semipagadas":
                        $q
                        ->where("estatus",1)
                        ->where("monto","<=",0);
                    break;
                    case "porvencer":
                        $q->where("fechavencimiento",">",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                    case "vencidas":
                        $q
                        ->where("fechavencimiento","<=",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                }
            })
            ->where(function($q) use ($keys,$qinvertircuentasPorPagarDetalles){

                if ($qinvertircuentasPorPagarDetalles==0) {
                    foreach ($keys as $i => $val) {
                        $q->orWhere("numfact","LIKE","%$val%");
                    }
                }else{
                    $qsi = cuentasporpagar::where(function($q) use ($keys){
                        foreach ($keys as $i => $val) {
                            $q->orWhere("numfact","LIKE","%$val%");
                        }
                    })->select("id");
                    
                    $q->whereNotIn("id",$qsi);
                }
            });
            
            $idsOrden = "";
            $getIds = $detalles->get(["id","numfact"]);
            foreach ($keys as $key => $val) {
                foreach ($getIds as $key => $getId) {
                    if (str_contains($getId["numfact"],$val)) {
                        $idsOrden .= $getId["id"].",";
                    }
                }
            }
            $idsOrden = rtrim($idsOrden, ",");
            if ($qinvertircuentasPorPagarDetalles==0&&$detalles->count()) {
                $detalles = $detalles->orderByRaw("FIELD(id,$idsOrden)");
            }
    
            foreach ($keys as $key => $split) {
                $esta = false;
                $id = "";
                $getIds->map(function($factnum) use ($split,&$esta,&$id){
                    if (str_contains($factnum["numfact"], $split)) {
                        $esta = true;
                        $id = $factnum["id"];
                    } 
                });
                array_push($fasts_no, ["id"=>$id, "numfact"=>$split, "show" => $esta]); 
            }
        }else{
            $detalles = $detalles
            ->where("aprobado",$cuentaporpagarAprobado)
            ->when( ($id_proveedor != "" && $id_proveedor != null),function($q) use ($id_proveedor){
                $q->where("id_proveedor",$id_proveedor);
            }) 
            ->when($sucursalcuentasPorPagarDetalles!="",function($q) use ($sucursalcuentasPorPagarDetalles){
                $q->where("id_sucursal",$sucursalcuentasPorPagarDetalles);
            })
            ->when($qcuentasPorPagarDetalles!="", function($q) use($qcuentasPorPagarDetalles, $sucursalcuentasPorPagarDetalles, $qcampoBusquedacuentasPorPagarDetalles) {
                $q->where(function($q) use ($sucursalcuentasPorPagarDetalles,$qcuentasPorPagarDetalles, $qcampoBusquedacuentasPorPagarDetalles) {
                    $q->orWhere($qcampoBusquedacuentasPorPagarDetalles,"LIKE","%$qcuentasPorPagarDetalles%")
                    ->when($sucursalcuentasPorPagarDetalles=="",function($qq) use ($qcuentasPorPagarDetalles) {
                        $qq->orWhereIn("id_sucursal",sucursal::where("nombre","LIKE","$qcuentasPorPagarDetalles%")->select("id"));
                    });
                });
                
            })
            ->when($categoriacuentasPorPagarDetalles!="",function($q) use ($categoriacuentasPorPagarDetalles) {
                $q->where("tipo","$categoriacuentasPorPagarDetalles");
            })
            ->when($tipocuentasPorPagarDetalles!="",function($q) use ($tipocuentasPorPagarDetalles) {
                if ($tipocuentasPorPagarDetalles=="DEUDA") {
                    $q->where("monto","<=",0);
                }else{
                    $q->where("monto",">",0);
                }
            })
            ->when($qcuentasPorPagarTipoFact=="",function($q) {
                $q->where("monto","<=",0)
                ->where("estatus","<>",2);
            })
            ->when($qcuentasPorPagarTipoFact!="",function($q) use ($qcuentasPorPagarTipoFact,$today){
                switch ($qcuentasPorPagarTipoFact) {
                    case "abonos":
                        $q->where("monto",">",0);
                    break;
                    case "pagadas":
                        $q->where("monto", "<=", "0")
                        ->where("estatus",2);
                        break;
                    case "semipagadas":
                        $q
                        ->where("estatus",1)
                        ->where("monto","<=",0);
                    break;
                    case "porvencer":
                        $q->where("fechavencimiento",">",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                    case "vencidas":
                        $q
                        ->where("fechavencimiento","<=",$today)
                        ->where("estatus",0)
                        ->where("monto","<=",0);
                    break;
                }
            })
            ->orderBy($qCampocuentasPorPagarDetalles,$OrdercuentasPorPagarDetalles);
        }
    
        $detalles_modified = $detalles->get()->map(function($q) use($today,$qcuentasPorPagarTipoFact, $todayWithoutDateTime) {
            $novedades_sum = $q->novedades->sum("monto");
            $q->monto = $q->monto-($novedades_sum<0?$novedades_sum:0);


            $q->monto_bruto = $q->monto;
            $q->monto = $q->monto-$q->monto_descuento;
    
            $fechavencimiento = new \DateTime($q->fechavencimiento);
            $monto_abonado = $q->monto_abonado?$q->monto_abonado:0;

            $monto = $q->monto;


            $balance = $q->balance-($novedades_sum<0?$novedades_sum:0);
            $id_sucursal_destino = $q->id_sucursal;
            
            $hoy = new \DateTime($todayWithoutDateTime);
            $vence = new \DateTime($q->fechavencimiento);
            $interval = $hoy->diff($vence);
            
            $q->dias = $interval->format('%R%a');
            $subtotal = 0;
            $q->items->map(function($item) use (&$subtotal,$id_sucursal_destino) {
                $subtotal += $item->cantidad * $item->basef;
                $item->id_producto_insucursal = null;
                $item->producto_insucursal = null;

                $vin = vinculossucursales::where("id_sucursal",13)->where("id_producto_local",$item->id_producto)->where("id_sucursal_fore",$id_sucursal_destino)->first();
                if ($vin) {
                    $item->id_producto_insucursal = $vin->idinsucursal_fore;

                    $producto_sucursal = inventario_sucursal::with("sucursal")->where("id_sucursal",$id_sucursal_destino)->where("idinsucursal",$vin->idinsucursal_fore)->first();
                    $item->producto_insucursal = $producto_sucursal;
                }

            });
            $q->sumitems = $subtotal;
            
            $pago_banco = 0;
            $pago_efectivo = 0;

            if ($q->montobs1) {
                $pago_banco += ($q->montobs1&&$q->tasabs1&&$q->tasabs1!="0.00"&&$q->tasabs1!=0.00? ($q->montobs1/$q->tasabs1) :0);
            }else if($q->montobs2){
                $pago_banco += ($q->montobs2&&$q->tasabs2&&$q->tasabs2!="0.00"&&$q->tasabs2!=0.00? ($q->montobs2/$q->tasabs2) :0);
            }else if($q->montobs3){
                $pago_banco += ($q->montobs3&&$q->tasabs3&&$q->tasabs3!="0.00"&&$q->tasabs3!=0.00? ($q->montobs3/$q->tasabs3) :0);
            }else if($q->montobs4){
                $pago_banco += ($q->montobs4&&$q->tasabs4&&$q->tasabs4!="0.00"&&$q->tasabs4!=0.00? ($q->montobs4/$q->tasabs4) :0);
            }else if($q->montobs5){
                $pago_banco += ($q->montobs5&&$q->tasabs5&&$q->tasabs5!="0.00"&&$q->tasabs5!=0.00? ($q->montobs5/$q->tasabs5) :0);
            }else{
                $pago_efectivo += $q->monto;
            }
            $q->pago_banco = $pago_banco;
            $q->pago_efectivo = $pago_efectivo;


            /* $q->fechaemision = date("d-m-Y", strtotime($q->fechaemision));
            $q->fechavencimiento = date("d-m-Y", strtotime($q->fechavencimiento));
            $q->fecharecepcion = date("d-m-Y", strtotime($q->fecharecepcion)); */
    
            if ($monto>0){
                $q->condicion = "abonos";
            
            }else if($q->estatus==1 && $monto<0){
                $q->condicion = "semipagadas";
            
            }else if($q->estatus==2 && $monto< 0){
                $q->condicion = "pagadas";
                $q->balance = $q->monto;
            
            }else if ($fechavencimiento<=$today && $q->estatus==0 && $monto<0){
                $q->condicion = "vencidas";
            
            }else if($fechavencimiento>$today && $q->estatus==0 && $monto<0){
                $q->condicion = "porvencer";
            }
            return $q;
        }); 
        $bs = (new CierresController)->getTasa()["bs"];
        
        $ret =  [
            "detalles" => $detalles_modified, 
            "balance" => $detalles_modified->sum("balance"), 
            "sum" => $detalles->get()->count(), 
            "proveedor" => $id_proveedor?proveedores::find($id_proveedor):null,
            "fasts_no" => $fasts_no,
            "tasa_referencial" => $bs
        ];
    
        if ($type=="buscar") {
            return $ret;
        }else{
            return view("reportes.conciliacionCuentasxPagar",$ret);
        }
    }
    function selectCuentaPorPagarProveedorDetalles(Request $req) {
        /* if (session("usuario")!="ao"&&session("usuario")!="omarE") {
            return;
        } */
        
        $id_proveedor = $req->id_proveedor=="null"?null:$req->id_proveedor;

        $qcampoBusquedacuentasPorPagarDetalles = $req->qcampoBusquedacuentasPorPagarDetalles;
        $qinvertircuentasPorPagarDetalles = $req->qinvertircuentasPorPagarDetalles;
        $cuentaporpagarAprobado = $req->cuentaporpagarAprobado;
        $categoriacuentasPorPagarDetalles = $req->categoriacuentasPorPagarDetalles;
        $tipocuentasPorPagarDetalles = $req->tipocuentasPorPagarDetalles;
        $qcuentasPorPagarTipoFact = $req->qcuentasPorPagarTipoFact;
        $qCampocuentasPorPagarDetalles = $req->qCampocuentasPorPagarDetalles;
        $qcuentasPorPagarDetalles = $req->qcuentasPorPagarDetalles;
        $OrdercuentasPorPagarDetalles = $req->OrdercuentasPorPagarDetalles;
        $sucursalcuentasPorPagarDetalles = $req->sucursalcuentasPorPagarDetalles;
        $numcuentasPorPagarDetalles = $req->numcuentasPorPagarDetalles;
        $type = $req->type;
        $id_facts_force = $req->id_facts_force;

        return $this->selectCuentaPorPagarProveedorDetallesFun([
            "id_proveedor" => $id_proveedor,
            "qcampoBusquedacuentasPorPagarDetalles" => $qcampoBusquedacuentasPorPagarDetalles,
            "qinvertircuentasPorPagarDetalles" => $qinvertircuentasPorPagarDetalles,
            "cuentaporpagarAprobado" => $cuentaporpagarAprobado,
            "categoriacuentasPorPagarDetalles" => $categoriacuentasPorPagarDetalles,
            "tipocuentasPorPagarDetalles" => $tipocuentasPorPagarDetalles,
            "qcuentasPorPagarTipoFact" => $qcuentasPorPagarTipoFact,
            "qCampocuentasPorPagarDetalles" => $qCampocuentasPorPagarDetalles,
            "qcuentasPorPagarDetalles" => $qcuentasPorPagarDetalles,
            "OrdercuentasPorPagarDetalles" => $OrdercuentasPorPagarDetalles,
            "sucursalcuentasPorPagarDetalles" => $sucursalcuentasPorPagarDetalles,
            "numcuentasPorPagarDetalles" => $numcuentasPorPagarDetalles,
            "type" => $type,
            "id_facts_force" => $id_facts_force,
        ]);
        
    }

    function sendlistdistribucionselect(Request $req) {
        DB::beginTransaction();

        try {
            $id_cxp = $req->id;
            $count = 0;
            $items = cuentasporpagar_items::with(["producto"])->where("id_cuenta",$id_cxp)->get();
            if (!$items->count()) {
                return Response::json(["msj"=>"Error: Factura SIN ITEMS","estado"=>false]);
            }
    
            $check_no_proce = pedidos::where("id_cxp",$id_cxp)->where("estado","1")->first();
            if (!$check_no_proce) { 
                $lastid = pedidos::orderBy("id","desc")->first("id");
                $cxp = cuentasporpagar::find($id_cxp);
                if (!$lastid) {$lastid = 0;}else{$lastid = $lastid->id;}
            
                $ped = new pedidos;
                $ped->id_cxp = $id_cxp;
                $ped->idinsucursal = ($lastid)+1;
                $ped->estado = 1;
                $ped->id_origen = 13;
                $ped->id_destino = $cxp->id_sucursal;//id Destino
                if ($ped->save()) {
                    $items = cuentasporpagar_items::with(["producto"])->where("id_cuenta",$id_cxp)->get();
                    foreach ($items as $ii => $cuentaitem) {
                        $inv_master = $cuentaitem->producto;
                        $items_pedidos = new items_pedidos;
                        $items_pedidos->id_producto = $cuentaitem->id_producto;
                        $items_pedidos->cantidad = $cuentaitem->cantidad;
                        $items_pedidos->monto = $inv_master["precio"]*$cuentaitem->cantidad;
                        $items_pedidos->id_pedido = $ped->id;
                        $items_pedidos->descuento = 0;
                        if ($items_pedidos->save()) {
                            $count++;  
                        }
                    }
                }
                DB::commit();
                return Response::json(["msj"=>"$count ITEMS PROCESADOS","estado"=>true]);
    
            }else{
                return Response::json(["msj"=>"Error: YA SE TRANSFIRIÓ","estado"=>false]);
            } 

        }catch (\Exception $e) {
            DB::rollback();
            return Response::json(["msj"=>"Error sendlistdistribucionselect".$e->getMessage()." ".$e->getLine(),"estado"=>false]);
        } 

        
    }
}
