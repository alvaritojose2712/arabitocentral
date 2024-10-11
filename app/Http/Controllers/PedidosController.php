<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\inventario_sucursal;
use App\Models\proveedores;
use App\Models\pedidos;
use App\Models\inventario;
use App\Models\sucursal;
use App\Models\items_pedidos;
use App\Models\vinculossucursales;
use App\Models\cuentasporpagar;


use App\Http\Requests\StorepedidosRequest;
use App\Http\Requests\UpdatepedidosRequest;

use Illuminate\Http\Request;
use Response;
use DB;

class PedidosController extends Controller
{   
    public function setPedidoInCentralFromMasters(Request $req)
    {
        $codigo_origen = $req->codigo_origen;
        
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
        
        $id_origen = $id_ruta["id_origen"];
        $id_destino = $req->id_sucursal;

        $pedidos = $req->pedidos;
        $type = $req->type;
        
        try {
            foreach ($pedidos as $key => $e) {
                if ($type=="add") {

                    $ped = new pedidos;

                    $ped->idinsucursal = $e["id"];
                    $ped->estado = 3;
                    // EL CORRECTO, MUTEADO PROVISIONALMENTE MIENTRAS COMPRAS CAMINA          $ped->estado = 1;
                    $ped->id_origen = $id_origen;
                    $ped->id_destino = $id_destino;//id Destino
                    if ($ped->save()) {
                        $count = 0;
                        foreach ($e["items"] as $k => $ee) {
                            $id_producto = null;
                            $check = inventario_sucursal::where("id_sucursal",13)->where("codigo_barras",$ee["producto"]["codigo_barras"])->first();
                            if ($check) {
                                $id_producto = $check->id;
                            }else{
                                $inv = inventario_sucursal::updateOrCreate([
                                    "id" => null,
                                ],[
                                    "id_sucursal" => 13,
                                    "idinsucursal" => null,
                                    
                                    "codigo_barras" => $ee["producto"]["codigo_barras"],
                                    "codigo_proveedor" => $ee["producto"]["codigo_proveedor"],
                                    "id_proveedor" => $ee["producto"]["id_proveedor"],
                                    "id_categoria" => $ee["producto"]["id_categoria"],
                                    "id_marca" => $ee["producto"]["id_marca"],
                                    "unidad" => $ee["producto"]["unidad"],
                                    "id_deposito" => $ee["producto"]["id_deposito"],
                                    "descripcion" => $ee["producto"]["descripcion"],
                                    "iva" => $ee["producto"]["iva"],
                                    "porcentaje_ganancia" => $ee["producto"]["porcentaje_ganancia"],
                                    "precio_base" => $ee["producto"]["precio_base"],
                                    "precio" => $ee["producto"]["precio"],
                                    "precio1" => $ee["producto"]["precio1"],
                                    "precio2" => $ee["producto"]["precio2"],
                                    "precio3" => $ee["producto"]["precio3"],
                                    "bulto" => $ee["producto"]["bulto"],
                                    "stockmin" => $ee["producto"]["stockmin"],
                                    "stockmax" => $ee["producto"]["stockmax"],
                                ]);
                                $id_producto = $inv->id;
                            }

                            /* $vinculo_envio = vinculossucursales::where("id_sucursal",13)
                            ->where("id_sucursal_fore",$origen)
                            ->where("id_producto_local",$id_producto)
                            ->first();

                            if (!$vinculo_envio) { */
                                $vinculo_envio = vinculossucursales::updateOrCreate([
                                    "id_sucursal" => 13, //CENTRAL
                                    "id_producto_local" => $id_producto, //PROD CENTRAL
                                    "idinsucursal_fore" => $ee["producto"]["id"], //PROD SUC
                                    "id_sucursal_fore" => $id_origen, //SUC
                                ],[
                                    "idinsucursal" => null, // INSUCURSAl, SOLO REF
                                ]);
                            /* } */

                            $vinculo_recepcion = vinculossucursales::where("id_sucursal",$id_destino)
                            ->where("id_sucursal_fore",$id_origen)
                            ->where("idinsucursal_fore",$ee["producto"]["id"])
                            ->first();

                            if ($vinculo_recepcion) {
                                vinculossucursales::updateOrCreate([
                                    "id_producto_local" => $id_producto, //PROD CENTRAL
                                    "id_sucursal" => 13, //CENTRAL
                                    "id_sucursal_fore" => $id_destino, //SUC
                                    "idinsucursal_fore" => $vinculo_recepcion->id_producto_local, //PROD SUC
                                ],[
                                    "idinsucursal" => null, // INSUCURSAl, SOLO REF
                                ]);
                            }


//////////////////////////////////////////////////////////////////////////////

                            $vinculo_recepcion_desdeenvio = vinculossucursales::where("id_sucursal",$id_origen)
                            ->where("id_sucursal_fore",$id_destino)
                            ->where("id_producto_local",$ee["producto"]["id"])
                            ->first();

                            if ($vinculo_recepcion_desdeenvio) {
                                vinculossucursales::updateOrCreate([
                                    "id_producto_local" => $id_producto, //PROD CENTRAL
                                    "id_sucursal" => 13, //CENTRAL
                                    "id_sucursal_fore" => $id_destino, //SUC
                                    "idinsucursal_fore" => $vinculo_recepcion_desdeenvio->idinsucursal_fore, //PROD SUC
                                ],[
                                    "idinsucursal" => null, // INSUCURSAl, SOLO REF
                                ]);
                            }
//////////////////////////////////////////////////////////////////////////////

                            $items_pedidos = new items_pedidos;
                            $items_pedidos->id_producto = $id_producto;
                            $items_pedidos->id_pedido = $ped->id;
                            $items_pedidos->cantidad = $ee["cantidad"];
                            $items_pedidos->descuento = $ee["descuento"];
                            $items_pedidos->monto = $ee["monto"];

                            if ($items_pedidos->save()) {
                                $count++;  
                            }
                                
                        }
                        return ["estado"=>true, "msj"=>"Desde Central: $count items exportados"];
                    }
                }else{
                    $f = pedidos::where("idinsucursal",$e["id"])->where("id_origen", $id_origen)->first(); 
                    if($f){

                        if ($f->estado==2) {
                            return ["estado"=>false, "msj"=>"Desde Central: Pedido ".$e["id"]." ya ha sido extraído"];
                        }else{
                            if ($f->delete()) {
                                return ["estado"=>true, "msj"=>"Desde Central: Pedido ".$e["id"]." eliminado de central"];
                            }else{
                                return ["estado"=>false, "msj"=>"No se encontró pedido ".$e["id"]];
                            }
                        }

                    };
                }
            }
        } catch (\Exception $e) {
            return ["estado"=>false, "msj"=>"Error: ".$e->getMessage()." ".$e->getLine()];
        }
    }
    function getPedidoCentralImport(Request $req) {

        $id_pedido = $req->id_pedido;
        $ped = $this->getPedidoFun($id_pedido);

        if ($id_pedido) {
            if (isset($ped["id"])) {
                $estado = $ped->estado;
                if ($estado==4) {
                    return ["estado"=>true,"msj"=>"","pedido"=>$ped];
                }else{
                    return ["estado"=>false,"msj"=>"Aún no se ha CHECKEADO en CENTRAL. ESTADO: ".$estado,"pedido"=>null];
                }
                
            }
        }
        return ["estado"=>false,"msj"=>"No se encontró PEDIDO getPedidoCentralImport ".$id_pedido,"pedido"=>null];

    }


    function sendTareasPendientesCentral(Request $req) {

        DB::beginTransaction();

        try {
            $data = $req->data;
            $codigo_origen = $req->codigo_origen;
    
            $id_pedido = $data["id_pedido"];
            $ids = $data["ids"];
    
            $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_sucursal = $id_ruta["id_origen"];
    
            $p = pedidos::find($id_pedido);
            $p->estado = 2;
            $p->save();
    
            foreach ($ids as $i => $e) {
                $id_productoincentral = $e["id_productoincentral"];
                $id_productosucursal = $e["id_productosucursal"];
    
                
                vinculossucursales::updateOrCreate([
                    "id_producto_local" => $id_productoincentral,
                    "id_sucursal_fore" => $id_sucursal, 
                    "idinsucursal_fore" => $id_productosucursal,
                ],[
                    "id_producto_local" => $id_productoincentral,
                    "id_sucursal_fore" => $id_sucursal, 
                    "id_sucursal" => 13,
                    "idinsucursal_fore" => $id_productosucursal,
                    "idinsucursal" => null, // INSUCURSAl, SOLO REF
                ]);
            }


            DB::commit();
            return ["estado"=>true, "msj" => "Éxito"];
        }catch (\Exception $e) {
            DB::rollback();
            return Response::json(["msj"=>"Error sendTareasPendientesCentral".$e->getMessage()." ".$e->getLine(),"estado"=>false]);
        } 
        


    }
    public function changeExtraidoEstadoPed(Request $req)
    {
        $p = pedidos::find($req->id);
        if ($p) {
            $p->estado = 2;
            $p->save();
        }
    }
    public function respedidos(Request $req)
    {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen,$codigo_origen);
        $id_origen = $id_ruta["id_origen"];


        $ped = pedidos::whereIn("estado",[1,3,4])
        ->where("id_destino",$id_origen)
        ->orderBy("id","desc")
        ->get()
        ->map(function($q){
           /*  $estado = $q->estado;
            $id_destino = $q->id_destino;
            $q->items = $q->items->map(function($q) use ($estado,$id_destino){
                if ($estado==4) {
                    $q->aprobado=true;
                }

                //////
                $idinsucursal_vinculo = null;
                $idinsucursal_producto_sugerido = null;
                $idinsucursal_producto = null;

                $vinculo_real = $q->vinculo_real;
                $vin = vinculossucursales::where("id_producto_local",$q->id_producto)->where("id_sucursal_fore",$id_destino)->first();
                if ($vin) {
                    $idinsucursal_vinculo =  $vin->idinsucursal_fore;
                    $idinsucursal_producto_sugerido = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vinculo_real)->first();
                    $idinsucursal_producto = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vin->idinsucursal_fore)->first();
                }
                $q->idinsucursal_vinculo = $idinsucursal_vinculo;
                $q->idinsucursal_producto_sugerido = $idinsucursal_producto_sugerido;
                $q->idinsucursal_producto = $idinsucursal_producto;

                /////


                return $q;
            });


            $q->base = $q->items->map(function($q){
                return $q->producto? $q->producto->precio_base*$q->cantidad:0;
            })->sum();
            $q->venta = $q->items->sum("monto"); */

            $q = $this->getPedidoFun($q->id);
            
            return $q;

        });
        return ["pedido"=>$ped,"codigo"=>$codigo_origen];
    }
    public function setConfirmFacturas(Request $req)
    {
        try {
            $ids = "";
            if ($req->facturas) {
                if (count($req->facturas)) {
                    foreach ($req->facturas as $key => $val) {
                        $fact = pedidos::find($val["id"]);

                        if ($fact) {
                            $fact->estado = 2;
                            if($fact->save()){
                                $ids .= $val["id"].",";
                            };
                        }
                    }
                    return Response::json(["msj"=>"Facturas Verificadas. ".$ids,"estado"=>true]);
                }
            }else{

                return Response::json(["msj"=>"Sin facturas","estado"=>true]);
            }

            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error de central: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    public function getPedidosList()
    {
        return pedidos::where("estado",0)->orderBy("id","desc")->get();
    }
    public function hacer_pedido($id,$id_pedido,$cantidad,$type)
    {
        if ($cantidad<0) {
            exit;
        }
        $old_ct = 0;
        
        if ($type=="ins") {
            $producto = inventario::find($id);
            $precio = $producto->precio;
            
            $setcantidad = $cantidad;
            $setprecio = $precio;
            
            $checkIfExits = items_pedidos::where("id_producto",$id)->where("id_pedido",$id_pedido)->first();
            
            if ($checkIfExits) {
                $old_ct = $checkIfExits["cantidad"];

                $setcantidad = $cantidad + $old_ct;
                $setprecio = $setcantidad*$precio;
            }


            items_pedidos::updateOrCreate(["id_producto"=>$id,"id_pedido"=>$id_pedido],[
                "id_producto" => $id,
                "id_pedido" => $id_pedido,
                "cantidad" => $setcantidad,
                "monto" => $setprecio
            ]);

            $ctSeter = (($producto->cantidad + ($old_ct)) - $setcantidad);
            $producto->cantidad = $ctSeter;
            $producto->save();

            // $this->checkFalla($id,$ctSeter);
        }else if($type=="upd"){
            $checkIfExits = items_pedidos::find($id);

            $producto = inventario::find($checkIfExits->id_producto);
            $precio = $producto->precio;

            $old_ct = $checkIfExits->cantidad;

            $setprecio = $cantidad*$precio;

            items_pedidos::updateOrCreate(["id"=>$id],[
                "cantidad" => $cantidad,
                "monto" => $setprecio
            ]);
            $ctSeter = (($producto->cantidad + ($old_ct)) - $cantidad);
            $producto->cantidad = $ctSeter;
            $producto->save();

            // $this->checkFalla($checkIfExits->id_producto,$ctSeter);
        }else if($type=="del"){
            $item = items_pedidos::find($id);
                $old_ct = $item->cantidad;
                $id_producto = $item->id_producto;
            
            $producto = inventario::find($id_producto);

            if($item->delete()){
                $ctSeter = $producto->cantidad + ($old_ct);
                $producto->cantidad = $ctSeter;
                $producto->save();

                // $this->checkFalla($id_producto,$ctSeter);

            }
        }

    }
    public function setCarrito(Request $req)
    {
        $id_producto = $req->id_producto;
        $ctSucursales = $req->ctSucursales;
        $producto = inventario::find($id_producto);

        if ($id_producto) {

            foreach ($ctSucursales as $i => $e) {
                $ct = $e["val"];
                $id_sucursal = $e["id"];
                $id_pedido = $e["id_pedido"];

                if ($ct) {
                    if ($id_pedido=="nuevo") {

                      //Crea Pedido

                        $new_pedido = new pedidos;
                        $new_pedido->estado = 0;
                        $new_pedido->id_sucursal = $id_sucursal;
                        $new_pedido->save();

                      //Next pedido num
                        $id_pedido = $new_pedido->id;
                    }

                    $this->hacer_pedido($id_producto,$id_pedido,$ct,"ins");
                }

            }
            
            return Response::json(["msj"=>"Agregado || ".$producto["descripcion"],"estado"=>"ok","num_pedido"=>$id_pedido]);


            
        }   
    }
    public function getPedidoFun($id_pedido)
    {
        $ped = pedidos::with(["origen","destino","sucursal","items"=>function($q){
            $q->with("producto");
        },"cxp"=>function($q){
            $q->with(["proveedor"]);
        }])
        ->find($id_pedido);
        if ($ped) {
            $ped->base = $ped->items->map(function($q){
                return $q->producto?$q->producto->precio_base*$q->cantidad:0;
            })->sum();
            $ped->venta = $ped->items->sum("monto");
            
            $estado = $ped->estado;
            $id_destino = $ped->id_destino;
            
            $ped->items = $ped->items->map(function($q) use ($estado,$id_destino){
                if ($estado==4) {
                    $q->aprobado=true;
                }
    
                ///
                $vinculo_real = $q->vinculo_real;
                $idinsucursal_vinculo = null;
                $idinsucursal_producto_sugerido = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vinculo_real)->first();
                $idinsucursal_producto = null;
                
                $vin = vinculossucursales::where("id_producto_local",$q->id_producto)->where("id_sucursal_fore",$id_destino)->first();
                if ($vin) {
                    $idinsucursal_vinculo =  $vin->idinsucursal_fore;
                    $idinsucursal_producto = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vin->idinsucursal_fore)->first();
                }
                $q->idinsucursal_vinculo = $idinsucursal_vinculo;
                $q->idinsucursal_producto_sugerido = $idinsucursal_producto_sugerido;
                $q->idinsucursal_producto = $idinsucursal_producto;
                ///
    
    
    
                return $q;
            });
    
            $c = cuentasporpagar::find($ped->id_cxp);
            $id_proveedor = null;
            $monto = null;
            $numfact = null;
    
            $fechavencimiento = null;
            $fecharecepcion = null;
            $fechaemision = null;
    
            if ($c) {
                $id_proveedor = $c->id_proveedor;
                $monto = $c->monto;
                $numfact = $c->numfact;
                $fechaemision = $c->fechaemision;
                $fechavencimiento = $c->fechavencimiento;
                $fecharecepcion = $c->fecharecepcion;
    
                
            }
    
            $ped->id_proveedor = $id_proveedor;
            $ped->monto = $monto;
    
            $ped->numfact = $numfact;
            $ped->fechaemision = $fechaemision;
            $ped->fechavencimiento = $fechavencimiento;
            $ped->fecharecepcion = $fecharecepcion;
            return $ped;
        }
        return ["estado" => false, "msj"=>"Error al FIND PEDIDO ".$id_pedido];
    }
    public function getPedido(Request $req)
    {
        $id = $req->id;

        return $this->getPedidoFun($id);
        
    }
    function revolverNovedadItemTrans(Request $req) {

        try {
            $iditem = $req->iditem;
            $type = $req->type;
            $accion = $req->accion;
            
            $item = items_pedidos::find($iditem);
            $ped = pedidos::find($item->id_pedido);
            $id_cxp = $ped->id_cxp;
            $id_destino = $ped->id_destino;
            $id_productolocal = $item->id_producto;
            if ($accion=="rechazar") {
                
                switch ($type) {
                    case 'barras_real':
                        $getcodigo_barras = inventario_sucursal::find($id_productolocal);
                        if ($getcodigo_barras) {
                            if (!$getcodigo_barras->codigo_barras) {
                                return ["estado"=>false,"msj"=>"No es POSIBLE RECHAZAR AJUSTE"];
                            }
                        }
                        break;
                    case 'alterno_real':
                        $getcodigo_proveedor = inventario_sucursal::find($id_productolocal);
                        if ($getcodigo_proveedor) {
                            if (!$getcodigo_proveedor->codigo_proveedor) {
                                return ["estado"=>false,"msj"=>"No es POSIBLE RECHAZAR AJUSTE"];
                            }
                        }
                        break;
                    case 'descripcion_real':
                        $getdescripcion = inventario_sucursal::find($id_productolocal);
                        if ($getdescripcion) {
                            if (!$getdescripcion->descripcion) {
                                return ["estado"=>false,"msj"=>"No es POSIBLE RECHAZAR AJUSTE"];
                            }
                        }
                        break;
                    }
                $item->update(["$type"=>null]);
            }
            
            if ($accion=="aprobar") {
    
               
                
    
                $vinculo_real = $item->vinculo_real;
    
                $barras_real = $item->barras_real;
                $alterno_real = $item->alterno_real;
                $descripcion_real = $item->descripcion_real;
    
                $ct_real = $item->ct_real;
    
                $cantidad = $item->cantidad;
                
    
                switch ($type) {
                    case 'vinculo_real':
                        vinculossucursales::updateOrCreate([
                            "id_producto_local"=>$id_productolocal,
                            "id_sucursal_fore"=>$id_destino,
                            "id_sucursal"=>13
                        ],[
                            "idinsucursal_fore"=>$vinculo_real,
                            "idinsucursal" => null
                        ]);

                        $modify_vin_real = items_pedidos::find($iditem);
                        $modify_vin_real->vinculo_real = null;
                        $modify_vin_real->save();
                    break;
                    case 'barras_real':
                        inventario_sucursal::find($id_productolocal)->update(["codigo_barras"=>(new InventarioSucursalController)->clean($barras_real,"codigo_barras")]);
                        $modify = items_pedidos::find($iditem);
                        $modify->barras_real = null;
                        $modify->save();
                    break;
                    case 'alterno_real':
                        inventario_sucursal::find($id_productolocal)->update(["codigo_proveedor"=>(new InventarioSucursalController)->clean($alterno_real,"codigo_proveedor")]);
                        $modify = items_pedidos::find($iditem);
                        $modify->alterno_real = null;
                        $modify->save();
                    break;
                    case 'descripcion_real':
                        inventario_sucursal::find($id_productolocal)->update(["descripcion"=>(new InventarioSucursalController)->clean($descripcion_real,"descripcion")]);
                        $modify = items_pedidos::find($iditem);
                        $modify->descripcion_real = null;
                        $modify->save();
                    break;
                    case 'ct_real':
                        if ($ct_real!=$cantidad) {
                            $c = cuentasporpagar::find($id_cxp);
                            if ($c) {
                                $ci = cuentasporpagar_items::where("id_cuenta",$id_cxp)->where("id_producto",$id_productolocal)->first();
                                if ($ci) {
                                    $ct = $ct_real-$cantidad;
                                    $basef = $ci->basef;
                                    $monto = $ct*$basef;
                                    $nota = compras_notascreditodebito::updateOrCreate([
                                        "id_producto" => $id_productolocal,
                                        "id_factura" => $id_cxp,
                                    ],[
                                        "tipo" => $ct<0?0:1,
                                        "num" => $c->numfact,
                                        "id_proveedor" => $c->id_proveedor,
                                        "id_sucursal" => $c->id_sucursal,
                                        "estatus" => 0,
                                        "monto" => $monto,
                                        "cantidad" => $ct,
                                        "id_factura" => $id_cxp,
                                    ]);
                                    if ($nota) {
                                        $item->cantidad = $ct_real;
                                        $item->ct_real = null;
                                        $item->save();
    
                                        $ci->cantidad = $ct_real;
                                        $ci->save();
                                    }
                                }
                            }
                        }
                    break;
                    
                }
            }
            
    
            $ped =  $this->getPedidoFun($item->id_pedido);
    
            return ["pedido"=>$ped,"estado"=>true,"msj"=>"Éxito"];
        } catch (\Exception $e) {
            return ["msj"=>"Error: ".$e->getMessage()." ".$e->getLine(),"estado"=>false];
           
        }
    }
    public function getPedidos(Request $req)
    {
        $qpedido = $req->qpedido;
        $qpedidoDateFrom = $req->qpedidoDateFrom;
        $qpedidoDateTo = $req->qpedidoDateTo;
        $qpedidoOrderBy = $req->qpedidoOrderBy;
        $qpedidoOrderByDescAsc = $req->qpedidoOrderByDescAsc;
        $qestadopedido = $req->qestadopedido;
        $qpedidosucursal = $req->qpedidosucursal;
        $qpedidosucursaldestino = $req->qpedidosucursaldestino;
        
        

        $limit = 1000000;
        if ($qpedidoDateFrom=="" AND $qpedidoDateTo=="") {
            $qpedidoDateFrom = "0000-00-00";
            $qpedidoDateTo = "9999-12-31";
            $limit = 50;
        }else if($qpedidoDateFrom == ""){
            $qpedidoDateFrom = "0000-00-00";
            $limit = 10;
        }else if($qpedidoDateTo == ""){
            $qpedidoDateTo = "9999-12-31";
            $limit = 10;
        }



        return pedidos::with(["origen","destino","sucursal","items"=>function($q){
            $q->with("producto");
        },"cxp"=>function($q){
            $q->with(["proveedor"]);
        }])
        ->when($qpedido, function($q) use ($qpedido) {
            $q->where(function($q) use($qpedido) {
                $q->orwhere("id","LIKE","$qpedido%")
                ->orwhere("idinsucursal","LIKE","$qpedido%");
            });
        })
        ->when($qestadopedido!="", function($q) use($qestadopedido) {
            $q->where("estado",$qestadopedido);
        })
        ->when($qpedidosucursal,function($q) use ($qpedidosucursal) {
            $q->where("id_origen",$qpedidosucursal);
        })
        ->when($qpedidosucursaldestino,function($q) use ($qpedidosucursaldestino) {
            $q->where("id_destino",$qpedidosucursaldestino);
        })
        
        ->whereBetween("created_at",["$qpedidoDateFrom 00:00:00","$qpedidoDateTo 23:59:59"])
        ->orderBy("created_at","desc")
        ->orderBy("id_origen","desc")
        ->limit($limit)
        ->get()
        ->map(function($q){
            /* $q->base = $q->items->map(function($q){
                return $q->producto?$q->producto->precio_base*$q->cantidad:0;
            })->sum();
            $q->venta = $q->items->sum("monto");

            $estado = $q->estado;
            $id_destino = $q->id_destino;
            $q->items = $q->items->map(function($q) use ($estado,$id_destino){
                if ($estado==4) {
                    $q->aprobado=true;
                }
                $idinsucursal_vinculo = null;
                $idinsucursal_producto_sugerido = null;
                $idinsucursal_producto = null;

                $vinculo_real = $q->vinculo_real;
                $vin = vinculossucursales::where("id_producto_local",$q->id_producto)->where("id_sucursal_fore",$id_destino)->first();
                if ($vin) {
                    $idinsucursal_vinculo =  $vin->idinsucursal_fore;
                    $idinsucursal_producto_sugerido = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vinculo_real)->first();
                    $idinsucursal_producto = inventario_sucursal::where("id_sucursal",$id_destino)->where("idinsucursal",$vin->idinsucursal_fore)->first();
                }
                $q->idinsucursal_vinculo = $idinsucursal_vinculo;
                $q->idinsucursal_producto_sugerido = $idinsucursal_producto_sugerido;
                $q->idinsucursal_producto = $idinsucursal_producto;

                return $q;
            }); */
            $q = $this->getPedidoFun($q->id);
            return $q;
        });
    }

    public function setCtCarrito(Request $req)
    {
        return $this->hacer_pedido($req->id,null,floatval($req->cantidad),"upd");
    }
    public function setDelCarrito(Request $req)
    {
        return $this->hacer_pedido($req->id,null,99,"del");
    }

    public function delPedido(Request $req)
    {
        try {
            $id = $req->id;
            $ped = pedidos::find($id);
            if ($ped) {
                if ($ped->estado==1) {
                    items_pedidos::where("id_pedido",$id)->delete();
                    pedidos::find($id)->delete();

                    return Response::json(["msj"=>"Éxito al eliminar. Pedido #".$id,"estado"=>true]);
                }else{
                    return Response::json(["msj"=>"Error: Pedido ha sido RECIBIDO".$id,"estado"=>false]);
                }
            }
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    function aprobarRevisionPedido(Request $req) {
        try {
            $id = $req->id;
            $estado = $req->estado;
            if ($id) {
                $ped = pedidos::with("items")->find($id);
                foreach ($ped["items"] as $i => $item) {
                    $codigo_barras = $item->producto->codigo_barras;
                    $codigo_proveedor = $item->producto->codigo_proveedor;
                    $descripcion = $item->producto->descripcion;
                     
                    if (!$codigo_barras) {
                        return ["estado"=>false,"msj"=>"Error: FALTA codigo_barras DEL ITEM ".$item->id];
                    }
                    if ($ped->id_origen==13) {
                        if (!$codigo_proveedor) {
                            return ["estado"=>false,"msj"=>"Error: FALTA codigo_proveedor DEL ITEM ".$item->id];
                        }
                    }
                    
                    if (!$descripcion) {
                        return ["estado"=>false,"msj"=>"Error: FALTA descripcion DEL ITEM ".$item->id];
                    }
                }   

                $ped->estado = $estado;
                $ped->save();
                
            }
            return Response::json(["msj"=>"Nuevo estado ".$estado,"estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    public function sendPedidoSucursal(Request $req)
    {

        try {
            $id = $req->id;
            if ($id) {
                $ped = pedidos::find($id);
                $ped->estado = 1;
                $ped->save();
            }
            return Response::json(["msj"=>"Éxito al enviar. Pedido #".$id,"estado"=>true]);
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }

    public function getPedidoPendSucursal(Request $req)
    {
        $sucursal = sucursal::where("codigo",$req->sucursal_code)->first();

        if (!$sucursal) {
            return Response::json([
                "msj"=>"No se encontró sucursal",
                "estado"=>false
            ]);
        }


        $ped = pedidos::with(["sucursal","items"=>function($q){
            $q->with("producto");
        }])
        ->where("estado",1)
        ->where("id_sucursal",$sucursal->id)
        ->orderBy("id","desc")
        ->get()
        ->map(function($q){
            $q->base = $q->items->map(function($q){
                return $q->producto->precio_base*$q->cantidad;
            })->sum();
            $q->venta = $q->items->sum("monto");
            return $q;

        });

        if ($ped) {
            return Response::json([
                "msj"=>"Tenemos algo :D",
                "pedido"=>$ped,
                "estado"=>true
            ]);
        }else{
            return Response::json([
                "msj"=>"No hay pedidos pendientes :(",
                "estado"=>false
            ]);
        }
    }

    public function showPedidoBarras(Request $req)
    {   
      $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

      $pedido = $this->getPedidoFun($req->id);
      $pedido->items->map(function($q) use ($generator){

        $base_bar =str_replace([".",","], "", number_format($q->producto->precio_base,2));
        $venta_bar =str_replace([".",","], "", number_format($q->producto->precio,2));
        $ct_bar =str_replace([".",","], "", number_format($q->cantidad,1));

        $bar = $this->barCodeCreate([
            "id_pedido" => $q->id_pedido,
            "id_producto" => $q->id_producto,
            "base_bar" => $base_bar,
            "venta_bar" => $venta_bar,
            "ct_bar" => $ct_bar,
        ]);
        $q->bar_clean = $bar;
        $q->bar = base64_encode($generator->getBarcode($bar, $generator::TYPE_CODE_128,2, 50));
        return $q;
      });
      $pedidobar = sprintf("%04d",$pedido->id) . sprintf("%04d",$pedido->items->count()) . $pedido->sucursal->codigo;
      $pedido->bar_pedido = base64_encode($generator->getBarcode($pedidobar, $generator::TYPE_CODE_128,2, 50));


        return view("reportes.pedidoBarras",["pedido"=>$pedido]);
    }
    public function barCodeCreate($item)
    {
        $id_pedido = sprintf("%04d",$item["id_pedido"]);
        $id_producto = sprintf("%04d",$item["id_producto"]);
        $base = sprintf("%06d",$item["base_bar"]);
        $venta = sprintf("%06d",$item["venta_bar"]);
        $cantidad = sprintf("%05d",$item["ct_bar"]);
        return $id_pedido.$id_producto.$base.$venta.$cantidad;
    }

    public function extraerPedidoPendSucursal(Request $req)
    {
        // code...
    }

    

    

    


}
