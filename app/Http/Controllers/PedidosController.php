<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\inventario_sucursal;
use App\Models\proveedores;
use App\Models\pedidos;
use App\Models\inventario;
use App\Models\sucursal;
use App\Models\items_pedidos;
use App\Http\Requests\StorepedidosRequest;
use App\Http\Requests\UpdatepedidosRequest;

use Illuminate\Http\Request;
use Response;

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
                    $ped->estado = 4;
                    // EL CORRECTO, MUTEADO PROVISIONALMENTE MIENTRAS COMPRAS CAMINA          $ped->estado = 1;
                    $ped->id_origen = $id_origen;
                    $ped->id_destino = $id_destino;//id Destino
                    if ($ped->save()) {
                        $count = 0;
                        foreach ($e["items"] as $k => $ee) {

                            $id_categoria = null;
                            if (isset($ee["producto"]["categoria"])) {
                                $ifcat =  categorias::where("descripcion",$ee["producto"]["categoria"]["descripcion"])->first();
                                if ($ifcat) {
                                    $id_categoria = $ifcat->id;
                                }else{
                                    $newcat = new categorias; 
                                    $newcat->descripcion = $ee["producto"]["categoria"]["descripcion"];
                                    $newcat->save();
                                    $id_categoria = $newcat->id;
                                }
                            }

                            $id_proveedor = null;
                            $ifpro =  proveedores::where("rif",$ee["producto"]["proveedor"]["rif"])->first();
                            if ($ifpro) {
                                $id_proveedor = $ifpro->id;
                            }else{

                                $id_proveedor = $ee["producto"]["proveedor"]["id"];

                            }

                                $inv = inventario_sucursal::updateOrCreate([
                                    "id_sucursal" => $id_origen,
                                    "idinsucursal" => $ee["producto"]["id"],
                                ],[
                                    "id_sucursal" => $id_origen,
                                    "idinsucursal" => $ee["producto"]["id"],
                                    
                                    "codigo_barras" => $ee["producto"]["codigo_barras"],
                                    "codigo_proveedor" => $ee["producto"]["codigo_proveedor"],
                                    "id_proveedor" => $id_proveedor,
                                    "id_categoria" => $id_categoria,
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
                                    "cantidad" => $ee["producto"]["cantidad"],
                                    "push" => $ee["producto"]["push"],
                                    "id_vinculacion" => $ee["producto"]["id_vinculacion"],
                                ]);
                                if ($inv) {
                                    
                                    $items_pedidos = new items_pedidos;
                                    
                                    $items_pedidos->id_producto = $inv->id;
                                    $items_pedidos->id_pedido = $ped->id;
                                    $items_pedidos->cantidad = $ee["cantidad"];
                                    $items_pedidos->descuento = $ee["descuento"];
                                    $items_pedidos->monto = $ee["monto"];

                                    if ($items_pedidos->save()) {
                                        $count++;  
                                    }
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


        $ped = pedidos::with(["destino","origen","items"=>function($q){
            $q->with(["producto"=>function($q){
                $q->with(["categoria","proveedor"]);
            }]);
        }])
        ->whereIn("estado",[1,3,4])
        ->where("id_destino",$id_origen)
        ->orderBy("id","desc")
        ->get()
        ->map(function($q){
            $estado = $q->estado;
            $q->items = $q->items->map(function($q) use ($estado){
                if ($estado==4) {
                    $q->aprobado=true;
                }
                return $q;
            });


            $q->base = $q->items->map(function($q){
                return $q->producto->precio_base*$q->cantidad;
            })->sum();
            $q->venta = $q->items->sum("monto");
            
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
    public function getPedidoFun($id)
    {
        $pedido = pedidos::with(["sucursal","items"=>function($q){
            $q->with("producto");
        }])->find($id);

        if ($pedido) {
            $pedido->base = $pedido->items->map(function($q){
                return $q->producto->precio_base*$q->cantidad;
            })->sum();
            $pedido->venta = $pedido->items->sum("monto");
        }

        return $pedido;
    }
    public function getPedido(Request $req)
    {
        $id = $req->id;

        return $this->getPedidoFun($id);
        
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
            $limit = 10;
        }else if($qpedidoDateFrom == ""){
            $qpedidoDateFrom = "0000-00-00";
            $limit = 10;
        }else if($qpedidoDateTo == ""){
            $qpedidoDateTo = "9999-12-31";
            $limit = 10;
        }



        return pedidos::with(["origen","destino","sucursal","items"=>function($q){
            $q->with("producto");
        }])
        ->when($qpedido, function($q) use ($qpedido) {
            $q->where(function($q) use($qpedido) {
                $q->orwhere("id","LIKE","$qpedido%")
                ->orwhere("idinsucursal","LIKE","$qpedido%");
            });
        })
        ->when($qestadopedido, function($q) use($qestadopedido) {
            $q->where("estado",$qestadopedido);
        })
        ->when($qpedidosucursal,function($q) use ($qpedidosucursal) {
            $q->where("id_origen",$qpedidosucursal);
        })
        ->when($qpedidosucursaldestino,function($q) use ($qpedidosucursaldestino) {
            $q->where("id_destino",$qpedidosucursaldestino);
        })
        
        ->whereBetween("created_at",["$qpedidoDateFrom 00:00:00","$qpedidoDateTo 23:59:59"])
        ->orderBy("id_origen","desc")
        ->limit($limit)
        ->get()
        ->map(function($q){
            $q->base = $q->items->map(function($q){
                return $q->producto->precio_base*$q->cantidad;
            })->sum();
            $q->venta = $q->items->sum("monto");
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
                if ($ped["estado"]!=2) {
                    if ($id) {
                       $items = items_pedidos::where("id_pedido",$id)->get();

                        foreach ($items as $key => $value) {
                           $this->hacer_pedido($value->id,$value->id_producto,99,"del");
                        }
                        pedidos::find($id)->delete();
                        return Response::json(["msj"=>"Éxito al eliminar. Pedido #".$id,"estado"=>true]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
    function aprobarRevisionPedido(Request $req) {
        try {
            $id = $req->id;
            if ($id) {
                $check = true;
                $items_pedidos = items_pedidos::where("id_pedido",$id)->get();

                foreach ($items_pedidos as $i => $item) {
                        
                    $inv = inventario_sucursal::find($item->id_producto);
                    if (!$inv->codigo_barras) {
                        if (!$item->barras_real) {
                           $check = false;
                        }
                        $inv->codigo_barras = $item->barras_real;
                        $inv->save();
                    }
                }
                if ($check) {
                    $ped = pedidos::find($id);
                    $ped->estado = 4;
                    $ped->save();
                }
                
            }
            return Response::json(["msj"=>"Éxito al Revisar. Pedido #".$id,"estado"=>true]);
            
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
