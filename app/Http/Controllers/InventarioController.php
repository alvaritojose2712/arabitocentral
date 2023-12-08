<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\facturas;
use App\Models\items_facturas;
use App\Models\moneda;
use App\Models\fallas;


use App\Http\Requests\StoreinventarioRequest;
use App\Http\Requests\UpdateinventarioRequest;
use Illuminate\Http\Request;
use Response;


class InventarioController extends Controller
{
public function sendInventario(Request $req)
{
    $inv = $req->inventario;


    
}
public function index(Request $req)
{
    $exacto = false;

    if (isset($req->exacto)) {
        if ($req->exacto=="si") {
            $exacto = "si";
        }
        if ($req->exacto=="id_only") {
            $exacto = "id_only";
        }
    }
    $cop = moneda::where("tipo",2)->orderBy("id","desc")->first();
    $bs = moneda::where("tipo",1)->orderBy("id","desc")->first();


    $data = [];

    $q = $req->qProductosMain;
    $num = $req->num;
    $itemCero = $req->itemCero;

    $orderColumn = $req->orderColumn;
    $orderBy = $req->orderBy;

    if ($q=="") {
        $data = inventario::with([
            "categoria",
            "marca",
            "catgeneral",
        ])
        ->limit($num)
        ->orderBy($orderColumn,$orderBy)
        ->get();
    }else{
        $data = inventario::with([
            "categoria",
            "marca",
            "catgeneral",
            
        ])
        
        ->where(function($e) use($itemCero,$q,$exacto){

            if ($exacto=="si") {
                $e->orWhere("codigo_barras","LIKE","$q")
                ->orWhere("codigo_proveedor","LIKE","$q");
            }elseif($exacto=="id_only"){

                $e->where("id","$q");
            }else{
                $e->orWhere("descripcion","LIKE","%$q%")
                ->orWhere("codigo_proveedor","LIKE","%$q%")
                ->orWhere("codigo_barras","LIKE","%$q%");

            }

        })
        ->limit($num)
        ->orderBy($orderColumn,$orderBy)
        ->get();
    }
   
    return $data;
    
}

    public function getEstaInventario(Request $req)
{
    $fechaQEstaInve = $req->fechaQEstaInve;

    $fecha1pedido = $req->fechaFromEstaInve;
    $fecha2pedido = $req->fechaToEstaInve;
    
    $orderByEstaInv = $req->orderByEstaInv;
    $orderByColumEstaInv = $req->orderByColumEstaInv;
    
    $tipoestadopedido = 1;

    
    return inventario::with([
        "proveedor",
        "categoria",
        "marca",
        "deposito",
    ])
    ->whereIn("id",function($q) use ($fecha1pedido,$fecha2pedido,$tipoestadopedido){
        $q->from("items_pedidos")
        ->whereIn("id_pedido",function($q) use ($fecha1pedido,$fecha2pedido,$tipoestadopedido){
            $q->from("pedidos")
            ->whereBetween("created_at",["$fecha1pedido 00:00:01","$fecha2pedido 23:59:59"])
            
            ->select("id");
        })
        ->select("id_producto");

    })
        ->where(function($q) use ($fechaQEstaInve)
    {
        $q->orWhere("descripcion","LIKE","%$fechaQEstaInve%")
        ->orWhere("codigo_proveedor","LIKE","%$fechaQEstaInve%");
        
    })
    ->selectRaw("*,@cantidadtotal := (SELECT sum(cantidad) FROM items_pedidos WHERE id_producto=inventarios.id AND created_at BETWEEN '$fecha1pedido 00:00:01' AND '$fecha2pedido 23:59:59') as cantidadtotal,(@cantidadtotal*inventarios.precio) as totalventa")
    ->orderByRaw(" $orderByColumEstaInv"." ".$orderByEstaInv)
    ->get();
    // ->map(function($q)use ($fecha1pedido,$fecha2pedido){
    //     $items = items_pedidos::whereBetween("created_at",["$fecha1pedido 00:00:01","$fecha2pedido 23:59:59"])
    //     ->where("id_producto",$q->id)->sum("cantidad");

    //     $q->cantidadtotal = $items
    //     // $q->items = $items->get();

    //     return $q;
    // })->sortBy("cantidadtotal");



}
public function reporteFalla(Request $req)
    {
        $id_proveedor = $req->id;

        $sucursal = sucursal::all()->first();
        $proveedor = proveedores::find($id_proveedor);

        if ($proveedor&&$id_proveedor) {
            $fallas = fallas::With("producto")->whereIn("id_producto",function($q) use ($id_proveedor)
            {
                $q->from("inventarios")->where("id_proveedor",$id_proveedor)->select("id");
            })->get();

            return view("reportes.fallas",[
                "fallas"=>$fallas, 
                "sucursal"=>$sucursal,
                "proveedor"=>$proveedor,
            ]);
        }


    }
public function delProductoFun($id)
{
    try {

        $i = inventario::find($id);
        
        //$this->setMovimientoNotCliente(null,$i->descripcion,$i->cantidad,$i->precio,"Eliminación de Producto");

        
        $i->delete();
        return true;   
    } catch (\Exception $e) {
        throw new \Exception("Error al eliminar. ".$e->getMessage(), 1);
        
    }
}
public function delProducto(Request $req)
{
    $id = $req->id;
    try {
        $this->delProductoFun($id);
        return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>$e->getMessage(),"estado"=>false]);
        
    }  
}
public function guardarNuevoProductoLote(Request $req)
{
    try {
        foreach ($req->lotes as $key => $ee) {
        if (isset($ee["type"])) {
            if ($ee["type"]==="update"||$ee["type"]==="new") {

                $this->guardarProducto(
                    $req->id_factura,
                    $ee["cantidad"],
                    $ee["id"],
                    $ee["codigo_barras"],
                    $ee["codigo_proveedor"],
                    $ee["unidad"],
                    $ee["id_categoria"],
                    $ee["descripcion"],
                    $ee["precio_base"],
                    $ee["precio"],
                    $ee["iva"],
                    $ee["id_marca"],
                    $ee["id_catgeneral"],
                );
            }else if ($ee["type"]==="delete") {
                $this->delProductoFun($ee["id"]);
            }
        }   
        }
            return Response::json(["msj"=>"Éxito","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
    }  
}
public function guardarNuevoProducto(Request $req)
{   
    try {
        $this->guardarProducto(
            $req->id_factura,
            $req->inpInvcantidad,
            $req->id,
            $req->inpInvbarras,
            $req->inpInvalterno,
            $req->inpInvunidad,
            $req->inpInvcategoria,
            $req->inpInvdescripcion,
            $req->inpInvbase,
            $req->inpInvventa,
            $req->inpInviva,
            $req->inpInvid_marca,
            $req->id_catgeneral
            );
            return Response::json(["msj"=>"Éxito","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
    }



        
}

public function guardarProducto(
    $req_id_factura,
    $req_inpInvcantidad,
    $req_id,
    $req_inpInvbarras,
    $req_inpInvalterno,
    $req_inpInvunidad,
    $req_inpInvcategoria,
    $req_inpInvdescripcion,
    $req_inpInvbase,
    $req_inpInvventa,
    $req_inpInviva,
    $req_inpInvid_marca,
    $id_catgeneral
){
    $id_factura = $req_id_factura;

    $ctInsert = $req_inpInvcantidad;

        try {
        
        $beforecantidad = 0;
        $ctNew = 0;
        $tipo = "";
        if (!$req_id) {
            $ctNew = $ctInsert;
            $tipo = "Nuevo";
        }else{
            $before = inventario::find($req_id);

            if ($before) {
                $beforecantidad = $before->cantidad;
                $ctNew = $ctInsert - $beforecantidad;
                $tipo = "Actualización";
            }
        }
        
        $insertOrUpdateInv = inventario::updateOrCreate([
            "id" => $req_id
        ],[
            "codigo_barras" => $req_inpInvbarras,
            "cantidad" => $ctInsert,
            "codigo_proveedor" => $req_inpInvalterno,
            "unidad" => $req_inpInvunidad,
            "id_categoria" => $req_inpInvcategoria,
            "descripcion" => $req_inpInvdescripcion,
            "precio_base" => $req_inpInvbase,
            "precio" => $req_inpInvventa,
            "iva" => $req_inpInviva,
            "id_marca" => $req_inpInvid_marca,
            "id_catgeneral" => $id_catgeneral,
        ]);

        /* $this->checkFalla($req_id,$ctInsert);
        $this->setMovimientoNotCliente($insertOrUpdateInv->id,"",$ctNew,"",$tipo);
        $this->insertItemFact($id_factura,$insertOrUpdateInv,$ctInsert,$beforecantidad,$ctNew,$tipo); */
        

        return true;   
    } catch (\Exception $e) {
        throw new \Exception("Error: ".$e->getMessage(), 1);
    }
}
public function insertItemFact($id_factura,$insertOrUpdateInv,$ctInsert,$beforecantidad,$ctNew,$tipo)
{
    $find_factura = factura::find($id_factura);

    if($insertOrUpdateInv && $find_factura){

        $id_pro = $insertOrUpdateInv->id;
        $check_fact = items_factura::where("id_factura",$id_factura)->where("id_producto",$id_pro)->first();

        if ($check_fact) {
            $ctNew = $ctInsert - ($beforecantidad - $check_fact->cantidad);
        }


        if ($ctNew==0) {
            items_factura::where("id_factura",$id_factura)->where("id_producto",$id_pro)->delete();
        }else{
            items_factura::updateOrCreate([
                "id_factura" => $id_factura,
                "id_producto" => $id_pro,
            ],[
                "cantidad" => $ctNew,
                "tipo" => $tipo,

            ]);

        }

    }
}
public function getFallas(Request $req)
{


    $qFallas = $req->qFallas;
    $orderCatFallas = $req->orderCatFallas;
    $orderSubCatFallas = $req->orderSubCatFallas;
    $ascdescFallas = $req->ascdescFallas;
    
    // $query_frecuencia = items_pedidos::with("producto")->select(['id_producto'])
    //     ->selectRaw('COUNT(id_producto) as en_pedidos, SUM(cantidad) as cantidad')
    //     ->groupBy(['id_producto']);

    // if ($orderSubCatFallas=="todos") {
    //     // $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="alta") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="media") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }else if ($orderSubCatFallas=="baja") {
    //     $query_frecuencia->having('cantidad', '>', )
    // }

    // return $query_frecuencia->get();
    if ($orderCatFallas=="categoria") {
        
        return fallas::with(["producto"=>function($q){
            $q->with(["proveedor","categoria"]);
        }])->get()->groupBy("producto.categoria.descripcion");

    }else if ($orderCatFallas=="proveedor") {
        return fallas::with(["producto"=>function($q){
            $q->with(["proveedor","categoria"]);
        }])->get()->groupBy("producto.proveedor.descripcion");

    }
}
public function setFalla(Request $req)
{   
    try {
        fallas::updateOrCreate(["id_producto"=>$req->id_producto],["id_producto"=>$req->id_producto]);
        
        return Response::json(["msj"=>"Falla enviada con Éxito","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        
    } 
}
public function delFalla(Request $req)
{   
    try {
        fallas::find($req->id)->delete();
        
        return Response::json(["msj"=>"Falla Eliminada","estado"=>true]);   
    } catch (\Exception $e) {
        return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        
    } 
}
public function checkFalla($id,$ct)
{   
    if ($id) {
        if ($ct>1) {
            $f = fallas::where("id_producto",$id);
            if ($f) {
                $f->delete();
            }
        }else if($ct<=0){

            fallas::updateOrCreate(["id_producto"=>$id],["id_producto"=>$id]);
        }
    }
}

public function reporteInventario(Request $req)
{
    $costo = 0;
    $venta = 0;

    $descripcion = $req->descripcion;
    $precio_base = $req->precio_base;
    $precio = $req->precio;
    $cantidad = $req->cantidad;
    $proveedor = $req->proveedor;
    $categoria = $req->categoria;
    $marca = $req->marca;

    $codigo_proveedor = $req->codigo_proveedor;
    $codigo_barras = $req->codigo_barras;

    $data= inventario::with("lotes","proveedor","categoria")->where(function($q) use ($codigo_proveedor,$codigo_barras,$descripcion,$precio_base,$precio,$cantidad,$proveedor,$categoria,$marca)
    {

        if($descripcion){$q->where("descripcion","LIKE",$descripcion."%");}
        if($codigo_proveedor){$q->where("codigo_proveedor","LIKE",$codigo_proveedor."%");}
        if($codigo_barras){$q->where("codigo_barras","LIKE",$codigo_barras."%");}

        if($precio_base){$q->where("precio_base",$precio_base);}
        if($precio){$q->where("precio",$precio);}
        if($cantidad){$q->where("cantidad",$cantidad);}
        if($proveedor){$q->where("id_proveedor",$proveedor);}
        if($categoria){$q->where("id_categoria",$categoria);}
        if($marca){$q->where("id_marca",$marca);}
    })->get()
    ->map(function($q) use (&$costo,&$venta)
    {
        if (count($q->lotes)) {
            $q->cantidad = $q->lotes->sum("cantidad"); 
        }
        $c = $q->cantidad*$q->precio_base;
        $v = $q->cantidad*$q->precio;

        $q->t_costo = number_format($c,"2"); 
        $q->t_venta = number_format($v,"2");
        
        $costo += $c;
        $venta += $v;

        return  $q;
    });
    $sucursal = sucursal::all()->first();
    $proveedores = proveedores::all();
    $categorias = categorias::all();
    
    
    return view("reportes.inventario",[
        "data"=>$data,
        "sucursal"=>$sucursal,
        "categorias"=>$categorias,
        "proveedores"=>$proveedores,

        "descripcion"=>$descripcion,
        "precio_base"=>$precio_base,
        "precio"=>$precio,
        "cantidad"=>$cantidad,
        "proveedor"=>$proveedor,
        "categoria"=>$categoria,
        "marca"=>$marca,

        "count" => count($data),
        "costo" => number_format($costo,"2"),
        "venta" => number_format($venta,"2"),

        "view_codigo_proveedor" => $req->view_codigo_proveedor==="off"?false:true,
        "view_codigo_barras" => $req->view_codigo_barras==="off"?false:true,
        "view_descripcion" => $req->view_descripcion==="off"?false:true,
        "view_proveedor" => $req->view_proveedor==="off"?false:true,
        "view_categoria" => $req->view_categoria==="off"?false:true,
        "view_id_marca" => $req->view_id_marca==="off"?false:true,
        "view_cantidad" => $req->view_cantidad==="off"?false:true,
        "view_precio_base" => $req->view_precio_base==="off"?false:true,
        "view_t_costo" => $req->view_t_costo==="off"?false:true,
        "view_precio" => $req->view_precio==="off"?false:true,
        "view_t_venta" => $req->view_t_venta==="off"?false:true,
        

    ]);
}
}
