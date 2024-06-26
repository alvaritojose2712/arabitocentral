<?php

namespace App\Http\Controllers;
use App\Models\inventario_sucursal_estadisticas;
use App\Models\marcas;

set_time_limit(300000);

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
        $cop = moneda::where("tipo",2)->orderBy("id","desc")->first();
        $bs = moneda::where("tipo",1)->orderBy("id","desc")->first();


        $data = [];

        $q = $req->qProductosMain;
        $num = $req->num;
        $itemCero = $req->itemCero;
        $qBuscarInventarioSucursal = $req->qBuscarInventarioSucursal;
        

        $orderColumn = "descripcion";
        $orderBy = $req->orderBy;

        if ($q=="") {
            $data = inventario_sucursal::with([
                "categoria",
                "catgeneral",
                "sucursales",
                "sucursal",
            ])
            ->when($qBuscarInventarioSucursal, function($q) use($qBuscarInventarioSucursal) {
                $q->where("id_sucursal",$qBuscarInventarioSucursal);
            })
            ->limit($num)
            ->orderBy("n1","asc")
            ->orderBy("n2","asc")
            ->orderBy("n3","asc")
            ->orderBy("id_marca","asc")
            ->get();
        }else{
            $data = inventario_sucursal::with([
                "categoria",
                "catgeneral",
                "sucursal",
                
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
            ->orderBy("n1","asc")
            ->orderBy("n2","asc")
            ->orderBy("n3","asc")
            ->orderBy("id_marca","asc")
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
            "id_proveedor" => $arr["id_proveedor"],
            "id_categoria" => $arr["id_categoria"],
            "id_marca" => $arr["id_marca"],
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
                            "id_sucursal" => 13,
                            "codigo_barras" => $ee["codigo_barras"],
                            "codigo_proveedor" => $ee["codigo_proveedor"],
                            "descripcion" => $ee["descripcion"],
                            "unidad" => $ee["unidad"],
                            "id_categoria" => $ee["id_categoria"],
                            "id_catgeneral" => $ee["id_catgeneral"],
                            "iva" => $ee["iva"],
                            "precio" => $ee["precio"],
                            "precio_base" => $ee["precio_base"],
                            "cantidad" => 0,
                        ]); 
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
        $i = inventario_sucursal::where("codigo_barras","LIKE","6928073674635")->whereNull("n1")->get();
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

    function getInventarioGeneral(Request $req) {
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

        $estadisticas = inventario_sucursal::with(["sucursal"])
        ->when($sucursalesAgregadasBusquedaEstadisticas,function($q) use($sucursalesAgregadasBusquedaEstadisticas) {
            $q->whereIn("id_sucursal",$sucursalesAgregadasBusquedaEstadisticas);
        })
        ->when($camposAgregadosBusquedaEstadisticas,function($q) use($camposAgregadosBusquedaEstadisticas) {
            foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                $q->where($e["campo"], $e["valor"]);
            }
        })
        ->limit($invsuc_num)
        ->orderBy("n1","desc")
        ->orderBy("id_sucursal","asc")
        ->orderBy("descripcion","asc")
        ->get()
        ->map(function($q) use ($today,$mesDate,$añoDate, $camposAgregadosBusquedaEstadisticas){
            $nombrefull = "";

            foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                $nombrefull .= ($q[$e["campo"]]?($q[$e["campo"]]." "):"");
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
        })
        ->groupBy(["nombrefull","sucursal.codigo"]);

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
