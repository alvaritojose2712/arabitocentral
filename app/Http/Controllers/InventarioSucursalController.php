<?php

namespace App\Http\Controllers;
use App\Models\inventario_sucursal_estadisticas;
use App\Models\marcas;

set_time_limit(600000);
ini_set('memory_limit', '4095M');

use Illuminate\Http\Request;
use App\Models\inventario_sucursal;
use App\Models\sucursal;
use App\Models\categorias;
use App\Models\proveedores;
use App\Models\moneda;
use App\Models\tareas;
use App\Models\inventario;
use App\Models\cuentasporpagar;
use App\Models\cuentasporpagar_items;

use App\Models\productonombre1;
use App\Models\productonombre2;
use App\Models\productonombre3;
use App\Models\productonombre4s;
use App\Models\productonombre5s;





use App\Http\Requests\Storeinventario_sucursalRequest;
use App\Http\Requests\Updateinventario_sucursalRequest;
use Response;



class InventarioSucursalController extends Controller
{

    
    function getDistinctNs() {
        $n1s = inventario_sucursal::selectRaw("DISTINCT(n1)")->get();
            foreach ($n1s as $i => $n1) {
                if ($n1->n1) {
                    productonombre1::updateOrCreate([
                        "nombre" => $n1->n1
                    ],[
                        "nombre" => $n1->n1
                    ]);
                }
            }
        $n2s = inventario_sucursal::selectRaw("DISTINCT(n2)")->get();
            foreach ($n2s as $i => $n2) {
                if ($n2->n2) {
                    productonombre2::updateOrCreate([
                        "nombre" => $n2->n2
                    ],[
                        "nombre" => $n2->n2
                    ]);
                }
            }
        $n3s = inventario_sucursal::selectRaw("DISTINCT(n3)")->get();
            foreach ($n3s as $i => $n3) {
                if ($n3->n3) {
                    productonombre3::updateOrCreate([
                        "nombre" => $n3->n3
                    ],[
                        "nombre" => $n3->n3
                    ]);
                }
            }
        $n4s = inventario_sucursal::selectRaw("DISTINCT(n4)")->get();
            foreach ($n4s as $i => $n4) {
                if ($n4->n4) {
                    productonombre4s::updateOrCreate([
                        "nombre" => $n4->n4
                    ],[
                        "nombre" => $n4->n4
                    ]);
                }
            }
        $n5s = inventario_sucursal::selectRaw("DISTINCT(n5)")->get();
            foreach ($n5s as $i => $n5) {
                if ($n5->n5) {
                    productonombre5s::updateOrCreate([
                        "nombre" => $n5->n5
                    ],[
                        "nombre" => $n5->n5
                    ]);
                }
            }
        $marcas = inventario_sucursal::selectRaw("DISTINCT(id_marca)")->get();
        foreach ($marcas as $i => $marca) {
            if ($marca->id_marca) {
                marcas::updateOrCreate([
                    "descripcion" => $marca->id_marca
                ],[
                    "descripcion" => $marca->id_marca
                ]);
            }
        }



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
        /* $cop = moneda::where("tipo",2)->orderBy("id","desc")->first();
        $bs = moneda::where("tipo",1)->orderBy("id","desc")->first(); */


        $data = [];

        $q = $req->qProductosMain;
        $itemCero = $req->itemCero;
        $qBuscarInventarioSucursal = $req->qBuscarInventarioSucursal;
        
        $orderColumn = $req->orderColumn;
        $orderBy = $req->orderBy;
        $num = $req->num;

        if ($q=="") {
            $data = inventario_sucursal::with([
                "categoria",
                "catgeneral",
                "sucursales",
                "sucursal",
                "proveedor"
            ])
            ->when($qBuscarInventarioSucursal, function($q) use($qBuscarInventarioSucursal) {
                $q->where("id_sucursal",$qBuscarInventarioSucursal);
            })
            ->limit($num)
            ->orderBy($orderColumn,$orderBy)
            ->get();
        }else{
            $data = inventario_sucursal::with([
                "categoria",
                "catgeneral",
                "sucursales",
                "sucursal",
                "proveedor",
                
            ])
            ->when($qBuscarInventarioSucursal, function($q) use($qBuscarInventarioSucursal) {
                $q->where("id_sucursal",$qBuscarInventarioSucursal);
            })
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
    public function changeEstatusProductoProceced(Request $req)
    {
        $ids = $req->ids;
        $id_sucursal = $req->id_sucursal;
        if (inventario_sucursal::whereIn("id",$ids)->update(["check"=>0])) {
            return Response::json(["estado"=>true,"msj"=>"Cambio de estatus exitoso"]);
        };
    }
    public function setInventarioFromSucursal(Request $req)
    {   
        $sucursal = sucursal::where("codigo",$req["sucursal"]["codigo"])->first();
        if (!$sucursal) {
            return Response::json(["estado"=>false, "msj"=>"Desde central: No se encontró sucursal".$req["sucursal"]["codigo"]]);
        }
        
            
        $count = 0;
        if (isset($req["inventario"])) {
            foreach ($req["inventario"] as $e) {
                $insertOrUpdateInv = inventario_sucursal::updateOrCreate([
                    "id_pro_sucursal" => $e["id"],
                    "id_sucursal" => $sucursal->id,
                ],[
                    "id_pro_sucursal" => $e["id"],
                    "id_pro_sucursal_fixed" => $e["id"],
                    "id_sucursal" => $sucursal->id,
                    "codigo_barras" => $e["codigo_barras"],
                    "codigo_proveedor" => $e["codigo_proveedor"],
                    "unidad" => $e["unidad"],
                    "id_categoria" => 1,
                    "descripcion" => $e["descripcion"],
                    "precio_base" => $e["precio_base"],
                    "precio" => $e["precio"],
                    "iva" => $e["iva"],
                    "id_proveedor" => 1,
                    "id_marca" => 1,
                    "id_deposito" => 1,
                    "porcentaje_ganancia" => $e["porcentaje_ganancia"]
                ]); 
                if ($insertOrUpdateInv) {
                    $count++;
                 } 

            }
            if ($insertOrUpdateInv) {
                return Response::json(["estado"=>true,"msj"=>"Desde Central: Exportación exitosa. Sucursal Code: ".$sucursal->codigo." | $count/".count($req["inventario"])." productos exitosos"]);
            }  
        }
    }
    public function getInventarioFromSucursal(Request $req)
    {
        $sucursal = sucursal::where("codigo",$req["sucursal"]["codigo"])->first();
        if (!$sucursal) {
            return Response::json(["estado"=>false, "msj"=>"Desde central: No se encontró sucursal->".$req["sucursal"]["codigo"]]);
        }
        return inventario_sucursal::with(["proveedor","categoria"])
        ->where("check",1)
        ->where("id_sucursal",$sucursal->id)
        ->get();

    }
    public function setCambiosInventarioSucursal(Request $req)
    {
        $sucursal = sucursal::where("codigo",$req->sucursal["codigo"])->first();
        if (!$sucursal) {
            return Response::json(["estado"=>false, "msj"=>"Desde central: No se encontró sucursal->".var_dump($req["sucursal"])]);
        }
        try {
          foreach ($req->productos as $key => $ee) {
            if (isset($ee["type"])) {
                if ($ee["type"]==="update"||$ee["type"]==="new") {

                    $insertOrUpdateInv = inventario_sucursal::updateOrCreate([
                        "id" => $ee["id"],
                    ],[

                        "id_pro_sucursal" => $ee["id_pro_sucursal"],
                        "id_pro_sucursal_fixed" => $ee["id_pro_sucursal_fixed"],
                        
                        "codigo_barras" => $ee["codigo_barras"],
                        "cantidad" => $ee["cantidad"],
                        "codigo_proveedor" => $ee["codigo_proveedor"],
                        "unidad" => $ee["unidad"],
                        "id_categoria" => $ee["id_categoria"],
                        "descripcion" => $ee["descripcion"],
                        "precio_base" => $ee["precio_base"],
                        "precio" => $ee["precio"],
                        "iva" => $ee["iva"],
                        "id_proveedor" => $ee["id_proveedor"],
                        "id_marca" => $ee["id_marca"],
                        "id_deposito" => $ee["id_deposito"],
                        "porcentaje_ganancia" => $ee["porcentaje_ganancia"],
                        "check"=>1
                    ]);
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
    public function delProductoFun($id)
    {
        try {

            $i = inventario_sucursal::find($id);
            
            $i->delete();
            return true;   
        } catch (\Exception $e) {
            throw new \Exception("Error al eliminar. ".$e->getMessage(), 1);
            
        }
    }
    public function retOrigenDestino($origen,$destino)
    {
        $query = sucursal::whereIn("codigo",[$origen,$destino])->get();

        $id_origen = $query->where("codigo",$origen)->first();
        $id_destino = $query->where("codigo",$destino)->first();

        return [
            "id_origen" => $id_origen?$id_origen->id:"no se encontró origen ".$origen,
            "id_destino" => $id_destino?$id_destino->id:"no se encontró destino ".$destino,
        ];
    }
    public function tiggerEventocentralEvent($sucursal)
    {
	    //event(new \App\Events\EventocentralEvent("autoResolveAllTarea",$sucursal));
    }
    public function getInventarioSucursalFromCentral(Request $req)
    {   
        $type = $req->type;
        
        $codigo_origen = $req->codigo_origen? $req->codigo_origen: "";
        $codigo_destino = $req->codigo_destino? $req->codigo_destino: "";
        
        //Acciones
        //
        try {
            switch ($type) {
                case 'inventarioSucursalFromCentral':
                    //Consultar nueva informacion en Sucursal desde central
                    $qinventario = $req->qinventario ? $req->qinventario : "";
                    $numinventario = $req->numinventario ? $req->numinventario : "";
                    $novinculados = $req->novinculados ? $req->novinculados : "";
                    $ids = $req->ids ? $req->ids : "";
                    
                    $id_ruta = $this->retOrigenDestino($codigo_origen,$codigo_destino);
                    $id_origen = $id_ruta["id_origen"];
                    $id_destino = $id_ruta["id_destino"];
                    $accion = "inventarioSucursalFromCentral";
    
                    $tareacheck = tareas::where("origen", $id_origen)
                    ->where("destino", $id_destino)
                    ->where("accion", $accion)->first();
                    if ($tareacheck) {
                        if ($tareacheck->estado==2) {
                            throw new \Exception("No puede consultar. Hay una tarea pendiente por resolver en Sucursal", 1);
                        }
                    }
                    
                    $tarea = tareas::updateOrCreate([
                        "origen" => $id_origen,
                        "destino" => $id_destino,
                        "accion" => $accion,
                    ],[
    
                        "origen" => $id_origen,
                        "destino" => $id_destino,
                        "accion" => $accion,
                        "solicitud" => json_encode([
                            "qinventario" => $qinventario,
                            "numinventario" => $numinventario,
                            "novinculados" => $novinculados,
                            "ids" => $ids,
                        ]),
                        //"respuesta" => "",
                        "estado" => 0,
                    ]);
                    if ($tarea) {
                        $this->tiggerEventocentralEvent($codigo_destino);
                        return "Desde central: Nueva tarea guardada ".$accion;
                    }
                    break;
                case 'inventarioSucursalFromCentralmodify':
                    $id_tarea = $req->id_tarea;
                    $find_tarea = tareas::find($id_tarea);
                    
                    
                    if (!$find_tarea) {
                        $id_ruta = $this->retOrigenDestino($codigo_origen,$codigo_destino);
                        $id_origen = $id_ruta["id_origen"];
                        $id_destino = $id_ruta["id_destino"];

                        $find_tarea = new tareas;
                        $find_tarea->origen = $id_origen;
                        $find_tarea->destino = $id_destino;
                        $find_tarea->accion = "inventarioSucursalFromCentral";

                        $find_tarea->respuesta = collect($req->productos)->map(function($q){
                            $q["estatus"] = 2;//Pasan a estatus 2 (Cargado)
                            return $q;
                        }); //Productos modificados o insertados //Estatus (1)
                        $find_tarea->estado = 2;
                        $find_tarea->solicitud = json_encode([
                            "insercion" => "modificacion|eliminacion" 
                        ]);
                        if ($find_tarea->save()) {
                            return "Se ha resuelto la tarea 'inventarioSucursalFromCentralmodify' con éxito. Destino: ".$codigo_destino;
                        }
                    }
                    if ($find_tarea->estado==2) {
                        
                        return "Error: No se puede Editar/Guardar debido a que hay una tarea de modificación aún no resuelta por la sucursal 'inventarioSucursalFromCentralmodify'";
                    }else{
    
                        $find_tarea->respuesta = collect($req->productos)->map(function($q){
                            $q["estatus"] = 2;//Pasan a estatus 2 (Cargado)
                            return $q;
                        }); //Productos modificados o insertados //Estatus (1)
                        $find_tarea->estado = 2;
                        $find_tarea->solicitud = json_encode([
                            "insercion" => "modificacion|eliminacion" 
                        ]);
                        if ($find_tarea->save()) {

                            $codigo_destino = sucursal::find($find_tarea->destino)->codigo;
                            $this->tiggerEventocentralEvent($codigo_destino);
                            return "Se ha resuelto la tarea 'inventarioSucursalFromCentralmodify' con éxito. Destino: ".$codigo_destino;
                        }
                    }
                    
    
                    break;
                case 'estadisticaspanelcentroacopio':
                    return [];
                    break;
                case 'gastospanelcentroacopio':
                    return [];
                    break;
                case 'cierrespanelcentroacopio':
                    return [];
                    break;
                case 'diadeventapanelcentroacopio':
                    return [];
                    break;
                case 'tasaventapanelcentroacopio':
                    //return moneda::where("id_sucursal",$id)->get();
                    break;
                
                
            }
        } catch (\Exception $e) {
            return "Error: ".$e->getMessage();
        }
    }
    public function setInventarioSucursalFromCentral(Request $req)
    {
        $codigo_origen = $req->codigo_origen;
        $codigo_destino = $req->codigo_destino;

        $id_ruta = $this->retOrigenDestino($codigo_origen,$codigo_destino);
        $id_origen = $id_ruta["id_origen"];
        $id_destino = $id_ruta["id_destino"];
        
        $accion = $req->type;

        switch ($accion) {
            case 'inventarioSucursalFromCentral':
                $accion = "inventarioSucursalFromCentral";
                $respuesta = tareas::where("origen",$id_origen)->where("destino",$id_destino)->where("accion",$accion)->get();

                if ($respuesta->first()) {
                    return $respuesta->first();
                }else {
                    return "Desde central: No se ha resuelto la tarea Origen:".$codigo_origen." Destino:".$codigo_destino." Acción:".$accion;
                }
                
                
                break;
            case 'fallaspanelcentroacopio':
                return [];
                break;
            case 'estadisticaspanelcentroacopio':
                return [];
                break;
            case 'gastospanelcentroacopio':
                return [];
                break;
            case 'cierrespanelcentroacopio':
                return [];
                break;
            case 'diadeventapanelcentroacopio':
                return [];
                break;
            case 'tasaventapanelcentroacopio':
                //return moneda::where("id_sucursal",$id)->get();
                break;
            
            
        }
        
    }

    function setInventarioSucursalFun($arr,$id_sucursal) {
        
        return inventario_sucursal::updateOrCreate([
            "id_sucursal" => $id_sucursal,
            "idinsucursal" => $arr["id"],
        ],[

            "idinsucursal" => $arr["id"],
            "id_sucursal" => $id_sucursal,
            "codigo_barras" => $arr["codigo_barras"],
            "codigo_proveedor" => $arr["codigo_proveedor"],
            /* "id_proveedor" => $arr["id_proveedor"],
            "id_categoria" => $arr["id_categoria"],
            "id_marca" => $arr["id_marca"], */
            "unidad" => $arr["unidad"],
            "id_deposito" => $arr["id_deposito"],
            "descripcion" => $arr["descripcion"],
            "iva" => $arr["iva"],
            "porcentaje_ganancia" => $arr["porcentaje_ganancia"],
            "precio_base" => $arr["precio_base"],
            "precio" => $arr["precio"],
            "precio1" => $arr["precio1"],
            "precio2" => $arr["precio2"],
            "precio3" => $arr["precio3"],
            "bulto" => $arr["bulto"],
            "stockmin" => $arr["stockmin"],
            "stockmax" => $arr["stockmax"],
            "cantidad" => $arr["cantidad"],
            "push" => $arr["push"],
            "id_vinculacion" => $arr["id_vinculacion"],
        ]);
    }

    


    public function sendInventarioCt($inventariodeldia,$id_origen) {
        try {
            $num = 0;
            foreach ($inventariodeldia as $i => $producto) {
                $insert = $this->setInventarioSucursalFun($producto, $id_origen);
                if ($insert) {
                    $num++;
                }
            }
            return "OK INVENTARIO ".$num." / ".count($inventariodeldia);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    function setEstadisticas(Request $req) {
        return $req->estadisticas;
    }

    function getBarrasCargaItems(Request $req) {
        $codigo_proveedor = $req->codigo_proveedor;
        $i = inventario_sucursal::where("codigo_proveedor",$codigo_proveedor)->first();
        if ($i) {
            return Response::json(["estado"=>true,"data"=>$i]);
        }else{
            return Response::json(["estado"=>false]);
        }
    }
    function guardarmodificarInventarioDici(Request $req) {
        try {
            $msj = "";
            $num = 0;
            foreach ($req->lotes as $i => $ee) {
                if (isset($ee["type"])) {
                    if ($ee["type"]==="update"||$ee["type"]==="new") {


                       $guardar = inventario_sucursal::updateOrCreate([
                            "id" => $ee["id"]? $ee["id"]:null
                        ],[
                            "n1" => $ee["n1"],
                            "n2" => $ee["n2"],
                            "n3" => $ee["n3"],
                            "n4" => $ee["n4"],
                            "n5" => $ee["n5"],
                            "id_marca" => $ee["id_marca"],
                            "id_categoria" => $ee["id_categoria"],
                            "id_catgeneral" => $ee["id_catgeneral"],
                            "id_proveedor" => $ee["id_proveedor"],

                            "stockmin" => $ee["stockmin"],
                            "stockmax" => $ee["stockmax"],
                            

                        ]); 
                        if ($guardar) {
                            $num++;
                        }else{
                            return Response::json(["msj"=>$msj, "estado"=>false]);   
                        }
                    }else if ($ee["type"]==="delete") {
                        //$this->delProductoFun($ee["id"]);
                    }
                }   
            }
            return Response::json(["msj"=> "PROCESADOS: ".count($req->lotes)." / ".$num, "estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage()." LINEA ".$e->getLine(),"estado"=>false]);
        } 
    }
    public function guardarNuevoProductoLote(Request $req)
    {
        try {
            $msj = "";
            $num = 0;
            foreach ($req->lotes as $i => $ee) {
                if (isset($ee["type"])) {
                    if ($ee["type"]==="update"||$ee["type"]==="new") {
                        $ee["id_factura"] = $req->id_factura;

                        $guardar = $this->guardarProducto($ee);
                        if ($guardar["estado"]) {
                            $num++;
                        }
                        //array_push($msj, $guardar["msj"]);

                        if (!$guardar["estado"]) {
                            return Response::json(["msj"=>$msj, "estado"=>false]);   
                        }
                    }else if ($ee["type"]==="delete") {
                        $this->delProductoFun($ee["id"]);
                    }
                }   
            }
            return Response::json(["msj"=> "PROCESADOS: ".count($req->lotes)." / ".$num, "estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage()." LINEA ".$e->getLine(),"estado"=>false]);
        }  
    }
    public function guardarProducto($arr){
        $id_factura = $arr["id_factura"];

        $cuentasporpagar = cuentasporpagar::find($id_factura);
        if ($cuentasporpagar->aprobado==1) {
            return ["msj"=>"Error: Cuenta ya aprobada, no se puede modificar", "estado"=>true];   
        }else{
            $sum_subtotal = 0;
            $fact_monto = 0;
            $Getfactmonto = cuentasporpagar::find($id_factura);
            if ($Getfactmonto) {
                $fact_monto = abs($Getfactmonto->monto);
            }
            cuentasporpagar_items::where("id_cuenta",$id_factura)->get()
            ->map(function($q) use (&$sum_subtotal){
                $sum_subtotal += $q->basef*$q->cantidad;
            });

            $sum_subtotal += $arr["cantidad"]*$arr["basef"];

            if ($sum_subtotal<=$fact_monto) {
                $check = true;
            }else{
                $check = false;
                return ["msj"=>"Valor de Items supera monto de factura [$arr[codigo_barras]]", "estado"=>false];   

            }
            //return ["msj"=>$sum_subtotal."______".$fact_monto, "estado"=>false];   

            if ($check) {
                $crearProducto = inventario_sucursal::updateOrCreate([
                    "id" => $arr["id"]? $arr["id"]:null
                ],[
                    "id_sucursal" => 13,
                    "codigo_barras" => $arr["codigo_barras"],
                    "codigo_proveedor" => $arr["codigo_proveedor"],
                    "descripcion" => $arr["descripcion"],
                    "unidad" => $arr["unidad"],
                    "id_categoria" => $arr["id_categoria"],
                    "id_catgeneral" => $arr["id_catgeneral"],
                    "iva" => $arr["iva"],
                    "precio" => $arr["precio"],
                    "precio_base" => $arr["precio_base"],
                    "cantidad" => 0,
                ]); 
                if ($crearProducto) {
                    $i = inventario_sucursal::find($crearProducto->id);
                    $i->idinsucursal = $crearProducto->id;
                    $i->save();
                    $cargarItem = cuentasporpagar_items::updateOrCreate([
                        "id_cuenta" => $id_factura,
                        "id_producto" => $crearProducto->id, 
                    ],[
                        "id_cuenta" => $id_factura,
                        "id_producto" => $crearProducto->id,
                        "cantidad" => $arr["cantidad"],
                        "basef" => $arr["basef"],
                        "base" => $arr["precio_base"],
                        "venta" => $arr["precio"],
                        "estado" => 0,
                    ]);
                    if ($cargarItem) {
                        return ["msj"=>"OK item ".$arr["codigo_barras"], "estado"=>true];   
                    }
                    
                }
            }
            return ["msj"=>"NO item ".$arr["codigo_barras"], "estado"=>false];   
        }
    }

    function autovincular() {
        $i = inventario_sucursal::whereNull("n1")->get();
        foreach ($i as $key => $e) {
            $get = inventario_sucursal::where("codigo_barras",$e->codigo_barras)->whereNotNull("n1")->first();
            if ($get) {
                $update = inventario_sucursal::find($e->id);
                $update->id_categoria = $get->id_categoria;
                $update->id_catgeneral = $get->id_catgeneral;
                $update->id_marca = $get->id_marca;
                $update->n1 = $get->n1;
                $update->n2 = $get->n2;
                $update->n3 = $get->n3;
                $update->n4 = $get->n4;
                $update->n5 = $get->n5;
                $update->save();
            }
        }
    }
    function clavesinve() {
        return [
            ['n1','ABRAZADERA','n2','','n3','','n4','','n5','1-1/4"X2"'],
            ['n1','ABRAZADERA','n2','','n3','','n4','','n5','1/2" - 3/4"'],
            ['n1','ACCESORIO','n2','BAÑO','n3','','n4','4 PIEZAS','n5',''],
            ['n1','ACOPLE','n2','HEMBRA','n3','','n4','1/4','n5',''],
            ['n1','ALICATE','n2','CORTA CABLE','n3','','n4','','n5','7P'],
            ['n1','ALICATE','n2','CORTA CABLE','n3','','n4','','n5','8P'],
            ['n1','ALICATE','n2','CORTE DIAGONAL','n3','AISLADO','n4','6P','n5',''],
            ['n1','ALICATE','n2','CORTE DIAGONAL','n3','','n4','','n5','7P'],
            ['n1','ALICATE','n2','CORTE ELECTRONICA','n3','','n4','','n5','5P'],
            ['n1','ALICATE','n2','PRESION','n3','PUNTA LARGA','n4','','n5','6P'],
            ['n1','ALICATE','n2','PRESION','n3','','n4','','n5','10P'],
            ['n1','ALICATE','n2','PRESION','n3','PUNTA LARGA','n4','','n5','4P'],
            ['n1','ALICATE','n2','MECANICO','n3','','n4','','n5','10P'],
            ['n1','ALICATE','n2','MECANICO','n3','MANGO GOMA','n4','','n5','10P'],
            ['n1','ALICATE','n2','MECANICO','n3','','n4','','n5','6P'],
            ['n1','ALICATE','n2','MECANICO','n3','','n4','','n5','8P'],
            ['n1','ALICATE','n2','MULTIUSO','n3','','n4','','n5',''],
            ['n1','ALICATE','n2','PRESION','n3','PUNTA LARGA','n4','','n5','9P'],
            ['n1','ALICATE','n2','PUNTA LARGA','n3','','n4','','n5','8P'],
            ['n1','ANILLO','n2','CERA','n3','INODORO','n4','3" O 4"','n5',''],
            ['n1','ANILLO','n2','GALVANIZADO','n3','','n4','','n5','2P'],
            ['n1','BOMBA + MANOMETRO','n2','AIRE','n3','PIE','n4','','n5',''],
            ['n1','BOMBA','n2','AIRE','n3','BICICLETA','n4','','n5',''],
            ['n1','BOMBILLO','n2','PLEGABLE','n3','TIPO FLOR','n4','','n5','36W'],
            ['n1','BOMBILLO','n2','LED','n3','TIPO VELA','n4','5W','n5','RE14'],
            ['n1','BORNES','n2','BATERIA','n3','','n4','2 PIEZAS','n5',''],
            ['n1','BREAKER','n2','EMPOTRAR','n3','','n4','','n5','1PX40 AMP'],
            ['n1','BREAKER','n2','EMPOTRAR','n3','','n4','','n5','2PX20 AMP'],
            ['n1','BREAKER','n2','EMPOTRAR','n3','','n4','','n5','2Px60 AMP'],
            ['n1','BREAKER','n2','SUPERFICIAL','n3','','n4','','n5','1PX15 AMP'],
            ['n1','BREAKER','n2','SUPERFICIAL','n3','','n4','','n5','1PX40 AMP'],
            ['n1','BREAKER','n2','SUPERFICIAL','n3','','n4','','n5','1PX60 AMP'],
            ['n1','BREAKER','n2','SUPERFICIAL','n3','','n4','','n5','2PX50 AMP'],
            ['n1','CABEZAL','n2','HEMBRA','n3','PARA INFLAR','n4','CAUCHO','n5',''],
            ['n1','CABEZAL','n2','MACHO','n3','PARA INFLAR','n4','CAUCHO','n5',''],
            ['n1','CABLE','n2','USB','n3','DOBLE FUNCION','n4','','n5',''],
            ['n1','CABLE','n2','USB','n3','IPHONE','n4','','n5','1M 2.2A'],
            ['n1','CADENA','n2','GALVANIZADA','n3','3/16P','n4','25KG','n5',''],
            ['n1','CADENA','n2','GALVANIZADA','n3','1/4P','n4','25KG','n5',''],
            ['n1','CAJE','n2','FUERTE','n3','','n4','','n5','25X35X25CM'],
            ['n1','CEPILLO','n2','CIRCULAR','n3','ALAMBRE','n4','TRENZADO MULTIROSCA','n5','4P'],
            ['n1','CEPILLO','n2','ALAMBRE','n3','ONDULADO 3"','n4','','n5',''],
            ['n1','CERRADURA','n2','CON MANILLA','n3','CURVA DERECHA','n4','','n5',''],
            ['n1','CERRADURA','n2','BAÑO','n3','POMO','n4','BOTON COBRE','n5',''],
            ['n1','CERRADURA','n2','DIENTE DE PERRO','n3','BL','n4','','n5',''],
            ['n1','CERRADURA','n2','MUEBLE','n3','2','n4','DORADA','n5',''],
            ['n1','CERRADURA','n2','MUEBLE','n3','2','n4','NIQUELADA','n5',''],
            ['n1','CERROJO','n2','LLAVES','n3','BRONCE','n4','','n5',''],
            ['n1','CERROJO','n2','LLAVES','n3','COBRE','n4','','n5',''],
            ['n1','CERROJO','n2','LLAVES','n3','MARIPOSA','n4','','n5',''],
            ['n1','CERROJO','n2','LLAVES','n3','MARIPOSA','n4','BRONCE','n5',''],
            ['n1','CERROJO','n2','LLAVES','n3','MARIPOSA','n4','COBRE','n5',''],
            ['n1','CINCEL','n2','1X12','n3','MANGO','n4','','n5',''],
            ['n1','CINCEL','n2','1/2X10"','n3','','n4','','n5',''],
            ['n1','CINTA','n2','PASA CABLE','n3','','n4','','n5','30M'],
            ['n1','CINTA','n2','PASA CABLE','n3','','n4','','n5','50M'],
            ['n1','CIZALLA','n2','MANGO GOMA','n3','','n4','8"','n5',''],
            ['n1','DISCO','n2','LIJA','n3','','n4','7"','n5','GRANO 60'],
            ['n1','DISCO','n2','TRONZADORA','n3','','n4','14P','n5',''],
            ['n1','DISCO','n2','ESMERILAR','n3','','n4','7"','n5','1/4 X 7/8'],
            ['n1','DISCO','n2','FLAP','n3','','n4','4 1/2P','n5','GRANO 100'],
            ['n1','DUCHA','n2','BAÑO','n3','','n4','1.5M','n5',''],
            ['n1','DUCHA','n2','TELEFONO','n3','BLACK','n4','','n5','1.2M'],
            ['n1','ESCUADRA','n2','CARPINTERIA','n3','','n4','8P','n5',''],
            ['n1','FIJADOR','n2','ROSCA ROJA','n3','','n4','EXTRA FUERTE','n5','10G'],
            ['n1','FIJADOR','n2','ROSCA AZUL','n3','','n4','','n5','10G'],
            ['n1','FILTRO','n2','DESAGUE','n3','FREGADERO','n4','8CM','n5',''],
            ['n1','FILTRO','n2','PURIFICADOR','n3','','n4','','n5',''],
            ['n1','FREGADERO','n2','ACERO','n3','INOXIDABLE','n4','DOBLE ESCURRIDERO','n5','84CMX56CM'],
            ['n1','FUNDA','n2','','n3','ANTIGOTEO','n4','','n5','9P'],
            ['n1','GARRUCHA','n2','DOBLE','n3','1 3/4"','n4','','n5','IMPORTADA'],
            ['n1','GUAYA','n2','DESTAPA CAÑERIA','n3','3MTS','n4','','n5',''],
            ['n1','HACHA','n2','SIN MANGO','n3','','n4','3.5LB','n5',''],
            ['n1','HERRAJE','n2','CON FLOTADOR','n3','','n4','COMPACTO','n5',''],
            ['n1','HERRAJE','n2','ONE PIECE','n3','DOBLE BOTON','n4','','n5',''],
            ['n1','INTERRUPTOR','n2','DOBLE','n3','TIPO BTICINO','n4','','n5',''],
            ['n1','JUEGO','n2','DADO RATCHET','n3','1/2','n4','12 PIEZAS','n5',''],
            ['n1','JUEGO','n2','DADO RATCHET','n3','1/2','n4','20 PIEZAS','n5',''],
            ['n1','JUEGO','n2','DADO RATCHET','n3','1/2','n4','32 PIEZAS','n5',''],
            ['n1','JUEGO','n2','DADO RATCHET','n3','1/4','n4','12 PIEZAS','n5',''],
            ['n1','JUEGO','n2','','n3','BOQUILLAS','n4','DE','n5','AIRE'],
            ['n1','JUEGO','n2','LLAVES','n3','COMBINADAS','n4','','n5','6 PIEZAS'],
            ['n1','JUEGO','n2','LLAVES','n3','COMBINADAS','n4','','n5','11 PIEZAS'],
            ['n1','JUEGO','n2','LLAVES','n3','COMBINADAS','n4','','n5','6 PIEZAS'],
            ['n1','JUEGO','n2','MINI ALICATES','n3','','n4','4 PIEZAS','n5',''],
            ['n1','JUEGO','n2','PISTOLA DE AIRE','n3','','n4','','n5',''],
            ['n1','JUEGO','n2','MANGUERA','n3','COMPRESOR','n4','','n5','6 PIEZAS'],
            ['n1','KIT','n2','SOPORTE','n3','CON','n4','5','n5','UND'],
            ['n1','LANA','n2','ACERO','n3','#3','n4','','n5','160G'],
            ['n1','LANA','n2','ACERO','n3','#3','n4','','n5','400G'],
            ['n1','LLAVE','n2','ALLEN','n3','PLEGABLE','n4','HEXAGONAL','n5','6 PIEZAS'],
            ['n1','LLAVE','n2','ALLEN','n3','TORX','n4','10 PIEZAS','n5',''],
            ['n1','LLAVE','n2','BATEA','n3','PARA PARED','n4','PLASTICO/CROMADA','n5',''],
            ['n1','LLAVE','n2','CHORRO','n3','PVC','n4','BLANCA','n5',''],
            ['n1','LLAVE','n2','CRUZ','n3','14-7/8,13/16,3/4,17MM','n4','','n5',''],
            ['n1','LLAVE','n2','TUBO','n3','','n4','','n5','12P'],  /**/
        ];
    }

    function getInventarioGeneral(Request $req) {

        $arr = $this->clavesinve();
        
        $today = (new NominaController)->today();
        $mesDate = date('Y-m' , strtotime($today));
        $añoDate = date('Y' , strtotime($today));

        $invsuc_q = $req->invsuc_q;
        $invsuc_num = $req->invsuc_num;
        $invsuc_orderBy = $req->invsuc_orderBy;
        $inventarioGeneralqsucursal = $req->inventarioGeneralqsucursal;

        $camposAgregadosBusquedaEstadisticas = $req->camposAgregadosBusquedaEstadisticas;
        $sucursalesAgregadasBusquedaEstadisticas = !count($req->sucursalesAgregadasBusquedaEstadisticas)? [] :$req->sucursalesAgregadasBusquedaEstadisticas->map(function($q) {
            return $q["id"]; 
        });


        $estadisticas = [];
        
        foreach ($this->clavesinve() as $i => $val) {
            $n1 = $val[1];
            $n2 = $val[3];
            $n3 = $val[5];
            $n4 = $val[7];
            $n5 = $val[9];
            
            $merge = inventario_sucursal::with(["sucursal"])
            ->when($sucursalesAgregadasBusquedaEstadisticas,function($q) use($sucursalesAgregadasBusquedaEstadisticas) {
                $q->whereIn("id_sucursal",$sucursalesAgregadasBusquedaEstadisticas);
            })
            ->where(function($q) use ($n1,$n2,$n3,$n4,$n5) {
                if ($n1) {
                    $q->where("n1",$n1);
                }
                if ($n2) {
                    $q->where("n2",$n2);
                }
                if ($n3) {
                    $q->where("n3",$n3);
                }
                if ($n4) {
                    $q->where("n4",$n4);
                }
                if ($n5) {
                    $q->where("n5",$n5);
                }
            })
            /* ->when($camposAgregadosBusquedaEstadisticas,function($q) use($camposAgregadosBusquedaEstadisticas) {
                foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                    $q->where($e["campo"], $e["valor"]);
                }
            }) */
           /*  ->limit($invsuc_num) */
            ->orderBy("n1","desc")
            ->orderBy("id_sucursal","asc")
            ->orderBy("descripcion","asc")
            ->get()
            ->map(function($q) use ($today,$mesDate,$añoDate, $camposAgregadosBusquedaEstadisticas,$n1,$n2,$n3,$n4,$n5){
                $nombrefull = "";
    
                /* foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                    $nombrefull .= ($q[$e["campo"]]?($q[$e["campo"]]." "):"");
                    } */
                if ($n1) {
                    $nombrefull .=  $q["n1"]? ($q["n1"]." "): "";
                }
                if ($n2) {
                    $nombrefull .=  $q["n2"]? ($q["n2"]." "): "";
                }
                if ($n3) {
                    $nombrefull .=  $q["n3"]? ($q["n3"]." "): "";
                }
                if ($n4) {
                    $nombrefull .=  $q["n4"]? ($q["n4"]." "): "";
                }
                if ($n5) {
                    $nombrefull .=  $q["n5"]? ($q["n5"]." "): "";
                }
                
                $q->nombrefull = $nombrefull? $nombrefull: "SIN ESPECIFICAR"; 
        
                $estadisticas = inventario_sucursal_estadisticas::where("id_sucursal",$q->id_sucursal)
                ->where("id_producto_insucursal",$q->idinsucursal)
                ->where("fecha","LIKE",$añoDate."%")
                ->orderBy("fecha","desc")
                ->get();
                $anual = [];
                foreach ($estadisticas as $i => $estadistica) {
                    $fecha = $estadistica["fecha"];
    
                    $año = date('Y' , strtotime($fecha));
                    $mes = date('M' , strtotime($fecha));
                    $ct = $estadistica["cantidad"];
    
    
                    if (!array_key_exists($año, $anual)) {
                        $anual[$año][$mes] = [
                            "ct" => $ct,
                            "dias" => 1
                        ];
                    }else{
                        if (array_key_exists($mes,$anual[$año])) {
                            $anual[$año][$mes] = [
                                "ct" => $anual[$año][$mes]["ct"]+$ct,
                                "dias" => $anual[$año][$mes]["dias"]+1,
                            ];
                        }else{
                            $anual[$año][$mes] = [
                                "ct" => $ct,
                                "dias" => 1,
                            ];
                        }
                    }
    
                }
                $q->anual = $anual;
                
                return $q;
            }) ;
            //$estadisticas = $estadisticas->merge($merge);
            $estadisticas = array_merge($estadisticas,$merge->toArray());
        }
        
        $estadisticas = collect($estadisticas)->groupBy(["nombrefull","sucursal.codigo"]);



        $sumas = [];
        
        foreach ($estadisticas as $fullname => $byscursales) {
            $sumas[$fullname] = [];
            $sumas[$fullname]["totalsucursales"] = [];

            foreach ($byscursales as $sucursalcode => $data) {
                $sumas[$fullname][$sucursalcode] = [];
                $totalsucursal = 0;
                foreach ($data as $i => $productos) {
                    $totalmismoproducto = 0;
                    foreach ($productos["anual"] as $año => $databyano) {
                        $totalaño = 0;
                        foreach ($databyano as $mes => $ctydias) {
                            $totalaño += $ctydias["ct"];
                            $sumas[$fullname]["totalsucursales"][$año."-".$mes] = isset($sumas[$fullname]["totalsucursales"][$año."-".$mes])?$sumas[$fullname]["totalsucursales"][$año."-".$mes]+$ctydias["ct"]:$ctydias["ct"];
                            $sumas[$fullname][$sucursalcode][$año."-".$mes] = isset($sumas[$fullname][$sucursalcode][$año."-".$mes])?$sumas[$fullname][$sucursalcode][$año."-".$mes]+$ctydias["ct"]:$ctydias["ct"];
                        }
                        $totalmismoproducto += $totalaño;
                        $sumas[$fullname]["totalsucursales"][$año] = isset($sumas[$fullname]["totalsucursales"][$año])?$sumas[$fullname]["totalsucursales"][$año]+$totalaño:$totalaño;
                        $sumas[$fullname][$sucursalcode][$año] = isset($sumas[$fullname][$sucursalcode][$año])?$sumas[$fullname][$sucursalcode][$año]+$totalaño:$totalaño;
                    }
                    $totalsucursal += $totalmismoproducto;
                }
                $sumas[$fullname]["totalsucursales"]["totalsucursal"] = isset($sumas[$fullname]["totalsucursales"]["totalsucursal"])?$sumas[$fullname]["totalsucursales"]["totalsucursal"]+$totalsucursal:$totalsucursal;
                $sumas[$fullname][$sucursalcode]["totalsucursal"] = $totalsucursal;
            }

        }

        return [
            "data" => $estadisticas,
            "sumas" => $sumas,
        ];


    }
    function importnagazaki() {
        $file_path = public_path("n.tsv");

        $delimiter = "\t";

        $fp = fopen($file_path, 'r');

        while ( !feof($fp) )
        {
            $line = fgets($fp, 2048);

            $data = str_getcsv($line, $delimiter);

            $n1 = $data[0]?trim($data[0]):"";
            $n2 = $data[1]?trim($data[1]):"";
            $n3 = $data[2]?trim($data[2]):"";
            $n4 = $data[3]?trim($data[3]):"";
            $n5 = $data[4]?trim($data[4]):"";
            $marca = $data[5]?trim($data[5]):"";
            $id_central = $data[6];

            $i = inventario_sucursal::find($id_central);
            if ($i) {
                $i->n1 = $n1;
                $i->n2 = $n2;
                $i->n3 = $n3;
                $i->n4 = $n4;
                $i->n5 = $n5;
                $i->id_marca = $marca;
                $i->save();
            }else{
                echo "No se encontró ".$id_central;
            }
        }                              

        fclose($fp);



    }

    
}
