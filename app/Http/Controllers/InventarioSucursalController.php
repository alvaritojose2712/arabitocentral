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
use App\Models\tareasSucursales;
use App\Models\vinculossucursales;






use App\Http\Requests\Storeinventario_sucursalRequest;
use App\Http\Requests\Updateinventario_sucursalRequest;
use Response;
use DB;



class InventarioSucursalController extends Controller
{

    function invsucursal(Request $req) {

        $data = $req->data;
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_sucursal = $id_ruta["id_origen"];

        try {
            //inventario_sucursal::where("id_sucursal",$id_sucursal)->delete();
            $all = json_decode(gzuncompress(base64_decode($data)),true);
            $num = 0;
           /*  $splitItems = array_chunk($all,500);
            foreach ($splitItems as $i => $e) {
                $tempArr = []; */
                foreach ($all as $producto) {
                    inventario_sucursal::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $producto["id"],
                    ],[
                        "codigo_proveedor" => $producto["codigo_proveedor"],
                        "codigo_barras" => $producto["codigo_barras"],
                        "id_proveedor" => $producto["id_proveedor"],
                        "id_categoria" => $producto["id_categoria"],
                        "id_marca" => $producto["id_marca"],
                        "unidad" => $producto["unidad"],
                        "id_deposito" => $producto["id_deposito"],
                        "descripcion" => $producto["descripcion"],
                        "iva" => $producto["iva"],
                        "porcentaje_ganancia" => $producto["porcentaje_ganancia"],
                        "precio_base" => $producto["precio_base"],
                        "precio" => $producto["precio"],
                        "cantidad" => $producto["cantidad"],
                        "bulto" => $producto["bulto"],
                        "precio1" => $producto["precio1"],
                        "precio2" => $producto["precio2"],
                        "precio3" => $producto["precio3"],
                        "stockmin" => $producto["stockmin"],
                        "stockmax" => $producto["stockmax"],
                        "id_vinculacion" => $producto["id_vinculacion"],
                        "push" => $producto["push"],
                    ]);
                    /* array_push($tempArr,$productoMod); */
                }
              /*   DB::table("inventario_sucursals")->insert($tempArr);
            } */

            return "OK INVENTARIO ".count($all);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
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
        
        $qProductosMain = $req->qProductosMain;
        $itemCero = $req->itemCero;
        $qBuscarInventarioSucursal = $req->qBuscarInventarioSucursal;
        $orderColumn = $req->orderColumn;
        $orderBy = $req->orderBy;
        $num = $req->num;
        $exacto = $req->exacto;
        
        $data = inventario_sucursal::with(["categoria","catgeneral","sucursales","sucursal","proveedor","vinculados"=>function($q) {
            $q->orderBy("id_sucursal_fore","asc");
        }])
        ->when($qBuscarInventarioSucursal, function($q) use($qBuscarInventarioSucursal) {
            $q->where("id_sucursal",$qBuscarInventarioSucursal);
        })
        ->when($qProductosMain!="", function($q) use ($itemCero,$qProductosMain,$exacto) {
            $q->where(function($e) use($itemCero,$q,$exacto,$qProductosMain){
                if ($exacto=="si") {
                    $e->orWhere("codigo_barras","LIKE","$qProductosMain")
                    ->orWhere("codigo_proveedor","LIKE","$qProductosMain");
                }elseif($exacto=="id_only"){
                    $e->where("id","$qProductosMain");
                }else{
                    $e->orWhere("descripcion","LIKE","%$qProductosMain%")
                    ->orWhere("codigo_proveedor","LIKE","%$qProductosMain%")
                    ->orWhere("codigo_barras","LIKE","%$qProductosMain%");
                }
            });
        })
        ->limit($num)
        ->orderBy($orderColumn,$orderBy)
        ->get()
        ->map(function($q) {
            $q->tarea = tareasSucursales::where("id_sucursal",$q->id_sucursal)->where("idinsucursal",$q->idinsucursal)->where("estado",0)->first();
            return $q;
        });
    
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

    
    


    public function sendInventarioCt($inventariodeldia,$id_sucursal) {
        try {
            //inventario_sucursal::where("id_sucursal",$id_sucursal)->delete();
            $all = json_decode(gzuncompress(base64_decode($inventariodeldia)),true);
            $num = 0;
           /*  $splitItems = array_chunk($all,500);
            foreach ($splitItems as $i => $e) {
                $tempArr = []; */
                foreach ($all as $producto) {
                    inventario_sucursal::updateOrCreate([
                        "id_sucursal" => $id_sucursal,
                        "idinsucursal" => $producto["id"],
                    ],[
                        "codigo_proveedor" => $producto["codigo_proveedor"],
                        "codigo_barras" => $producto["codigo_barras"],
                        "id_proveedor" => $producto["id_proveedor"],
                        "id_categoria" => $producto["id_categoria"],
                        "id_marca" => $producto["id_marca"],
                        "unidad" => $producto["unidad"],
                        "id_deposito" => $producto["id_deposito"],
                        "descripcion" => $producto["descripcion"],
                        "iva" => $producto["iva"],
                        "porcentaje_ganancia" => $producto["porcentaje_ganancia"],
                        "precio_base" => $producto["precio_base"],
                        "precio" => $producto["precio"],
                        "cantidad" => $producto["cantidad"],
                        "bulto" => $producto["bulto"],
                        "precio1" => $producto["precio1"],
                        "precio2" => $producto["precio2"],
                        "precio3" => $producto["precio3"],
                        "stockmin" => $producto["stockmin"],
                        "stockmax" => $producto["stockmax"],
                        "id_vinculacion" => $producto["id_vinculacion"],
                        "push" => $producto["push"],
                    ]);
                    /* array_push($tempArr,$productoMod); */
                }
              /*   DB::table("inventario_sucursals")->insert($tempArr);
            } */

            return "OK INVENTARIO ".count($all);
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
    function clean($val,$type) {
        $val = strtoupper($val);
        
        if ($type=="descripcion") {
            return str_replace(["'",'"', '*'], "", $val) ;
        }
        if ($type=="codigo_barras" ) {
            return str_replace(["'",'"', '*'," "], "", $val);
        }
        if ($type=="codigo_proveedor") {
            return $val;
        }
    }
    function guardarmodificarInventarioDici(Request $req) {
        try {
            $msj = "";
            $num = 0;
            foreach ($req->lotes as $i => $ee) {
                if (isset($ee["type"])) {
                    if ($ee["type"]==="update"||$ee["type"]==="new") {

                        if (isset($ee["id_sucursal"])) {
                            if ($ee["id_sucursal"]!=13) {
                                $guardar = (new TareasSucursalesController)->setTarea($ee,1); 
                                 if ($guardar) {
                                     $num++;
                                 }else{
                                     return Response::json(["msj"=>$msj, "estado"=>false]);   
                                 }
                            }else{
                                $crearProducto = inventario_sucursal::updateOrCreate([
                                    "id" => $ee["id"]? $ee["id"]:null
                                ],[
                                    "id_sucursal" => 13,
                                    
                                    "codigo_barras" => $this->clean(@$ee["codigo_barras"],"codigo_barras"),
                                    "codigo_proveedor" => $this->clean(@$ee["codigo_proveedor"],"codigo_proveedor"),
                                    "descripcion" => $this->clean(@$ee["descripcion"],"descripcion"),
                                    "codigo_proveedor2" => @$ee["codigo_proveedor2"],
                                    "id_deposito" => @$ee["id_deposito"],
                                    "unidad" => @$ee["unidad"],
                                    "iva" => @$ee["iva"],
                                    "porcentaje_ganancia" => @$ee["porcentaje_ganancia"],
                                    "precio_base" => @$ee["precio_base"],
                                    "precio" => @$ee["precio"],
                                    "precio1" => @$ee["precio1"],
                                    "precio2" => @$ee["precio2"],
                                    "precio3" => @$ee["precio3"],
                                    "bulto" => @$ee["bulto"],
                                    "cantidad" => @$ee["cantidad"],
                                    "push" => @$ee["push"],
                                    "id_vinculacion" => @$ee["id_vinculacion"],
                                    "n1" => @$ee["n1"],
                                    "n2" => @$ee["n2"],
                                    "n3" => @$ee["n3"],
                                    "n4" => @$ee["n4"],
                                    "n5" => @$ee["n5"],
                                    "id_proveedor" => @$ee["id_proveedor"],
                                    "id_categoria" => @$ee["id_categoria"],
                                    "id_catgeneral" => @$ee["id_catgeneral"],
                                    "id_marca" => @$ee["id_marca"],
                                    "id_marca" => @$ee["id_marca"],
                                    "stockmin" => @$ee["stockmin"],
                                    "stockmax" => @$ee["stockmax"],
                                ]); 
                            }
                        }
                    }else if ($ee["type"]==="delete") {
                        //$this->delProductoFun($ee["id"]);
                    }

                }   
            }
            return Response::json(["msj"=> "PROCESADOS: ".count($req->lotes)." / ".$num, "estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage()." LINEA ".$e->getLine()." FILE ".$e->getFile(),"estado"=>false]);
        } 
    }
    public function guardarNuevoProductoLote(Request $req)
    {
        try {
            $msj = "";
            $num = 0;

            $id_factura = $req->id_factura;

            $cuentasporpagar = cuentasporpagar::find($id_factura);
            if ($cuentasporpagar->aprobado==1) {
                return ["msj"=>"Error: Cuenta ya aprobada, no se puede modificar", "estado"=>false];
            }else{
                $alternoduplicado = [];
                $barrasduplicado = [];
                foreach ($req->lotes as $i => $ee) {
                    
                    if (!array_key_exists($ee["codigo_proveedor"],$alternoduplicado)) {
                        $alternoduplicado[$ee["codigo_proveedor"]] = 1;
                    }else{
                        return ["msj"=>"Error: codigo_proveedor DUPLICADO: ".$ee["codigo_proveedor"],"estado"=>false];
                    }

                    if ($ee["codigo_barras"]!="") {
                        if (!array_key_exists($ee["codigo_barras"],$barrasduplicado)) {
                            $barrasduplicado[$ee["codigo_barras"]] = 1;
                        }else{
                            return ["msj"=>"Error: codigo_barras DUPLICADO: ".$ee["codigo_barras"],"estado"=>false];
                        }
                    }


                   /*  if (!$ee["codigo_barras"]) {
                        return ["msj"=>"Error: codigo_barras NO VALIDO","estado"=>false];
                    } */
                    if (!$ee["codigo_proveedor"]) {
                        return ["msj"=>"Error: codigo_proveedor NO VALIDO","estado"=>false];
                    }
                    if (!$ee["descripcion"]) {
                        return ["msj"=>"Error: descripcion NO VALIDO","estado"=>false];
                    }
                    if (!$ee["id_categoria"]) {
                        return ["msj"=>"Error: id_categoria NO VALIDO","estado"=>false];
                    }
                    if (!$ee["id_catgeneral"]) {
                        return ["msj"=>"Error: id_catgeneral NO VALIDO","estado"=>false];
                    }
                    if (!$ee["unidad"]) {
                        return ["msj"=>"Error: unidad NO VALIDO","estado"=>false];
                    }
                     
                    if ($ee["precio"]=="") {
                        return ["msj"=>"Error: precio NO VALIDO","estado"=>false];
                    }
                    if ($ee["precio_base"]=="") {
                        return ["msj"=>"Error: precio_base NO VALIDO","estado"=>false];
                    }
                    if ($ee["cantidad"]=="") {
                        return ["msj"=>"Error: cantidad NO VALIDO","estado"=>false];
                    } 
                     


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
    
                    $sum_subtotal += $ee["cantidad"]*$ee["basef"];
    
                    if ($sum_subtotal<=$fact_monto) {
                    }else{
                        //return ["msj"=>"Valor de Items supera monto de factura [$ee[codigo_barras]]", "estado"=>false];
                    }
                    if (!$ee["id"]) {
                        $barras = inventario_sucursal::where("id_sucursal",13)->where("codigo_barras",$ee["codigo_barras"])->first();
                        $codigo_proveedor = inventario_sucursal::where("id_sucursal",13)->where("codigo_proveedor",$ee["codigo_proveedor"])->first();
                        $descripcion = inventario_sucursal::where("id_sucursal",13)->where("descripcion",$ee["descripcion"])->first();
    
                        if(!preg_match("/^[A-Za-z\\-0-9]*$/", $ee["codigo_barras"])){
                            return ["msj"=>"CÓDIGO DE BARRAS SOLO DEBE CONTENER LETRAS, NÚMEROS O GUIONES [$ee[codigo_barras]]", "estado"=>false];   
                        }
                        if(!preg_match("/^[A-Za-z\\-0-9]*$/", $ee["codigo_proveedor"])){
                            return ["msj"=>"CÓDIGO ALTERNO SOLO DEBE CONTENER LETRAS, NÚMEROS O GUIONES [$ee[codigo_proveedor]]", "estado"=>false];   
                        }
    
    
                        if ($barras) {
                            return ["msj"=>"CÓDIGO DE BARRAS [$ee[codigo_barras]] YA EXISTE en PRODUCTO MAESTRO", "estado"=>false];   
                        }
                        if ($codigo_proveedor) {
                            return ["msj"=>"CÓDIGO ALTERNO [$ee[codigo_proveedor]] YA EXISTE en PRODUCTO MAESTRO", "estado"=>false];   
                        }
                        if ($descripcion) {
                            return ["msj"=>"DESCRIPCIÓN [$ee[descripcion]] YA EXISTE en PRODUCTO MAESTRO", "estado"=>false];   
                        }
                    }
                }
            }




            foreach ($req->lotes as $i => $ee) {
                if (isset($ee["type"])) {
                    if ($ee["type"]==="update"||$ee["type"]==="new") {
                        $ee["id_factura"] = $id_factura;
                        $this->guardarProducto($ee);
                    }else if ($ee["type"]==="delete") {
                        //$this->delProductoFun($ee["id"]);
                    }
                }   
            }
            return Response::json(["msj"=> "PROCESADOS: ".count($req->lotes), "estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage()." LINEA ".$e->getLine(),"estado"=>false]);
        }  
    }
    function verificarproductomaestro(Request $req) {
        $lotes = $req->lotes;

        foreach ($lotes as $i => $e) {
            $ispermission = false;
            $ispermissionData = [];
            $ispermissionType = null;

            $codigo_proveedor = $e["codigo_proveedor"];
            $alternomaestro = inventario_sucursal::where("id_sucursal",13)->where("codigo_proveedor",$codigo_proveedor)->first();

            if ($alternomaestro) {
                $lotes[$i]["id"] = $alternomaestro->id;
                $ispermission = true;
                $ispermissionData = $alternomaestro;
                $ispermissionType = "maestro";
            }else{
                $alternoenotrasucursal = inventario_sucursal::with("sucursal")->where("codigo_proveedor",$codigo_proveedor)->first();
                if ($alternoenotrasucursal) {
                    $lotes[$i]["id"] = null;
                    $ispermission = true;
                    $ispermissionData = $alternoenotrasucursal;
                    $ispermissionType = $alternoenotrasucursal->sucursal->codigo;
                }
            }

            if ($ispermission) {
                $lotes[$i]["codigo_barras_antes"] = $e["codigo_barras"];
                $lotes[$i]["descripcion_antes"] = $e["descripcion"];

                $lotes[$i]["codigo_barras"] = $ispermissionData["codigo_barras"];
                $lotes[$i]["descripcion"] = $ispermissionData["descripcion"];
                $lotes[$i]["codigo_proveedor"] = $ispermissionData["codigo_proveedor"];
                $lotes[$i]["unidad"] = $ispermissionData["unidad"];
                $lotes[$i]["id_categoria"] = $ispermissionData["id_categoria"];
                $lotes[$i]["id_catgeneral"] = $ispermissionData["id_catgeneral"];
                $lotes[$i]["iva"] = $ispermissionData["iva"];
                $lotes[$i]["id_marca"] = $ispermissionData["id_marca"];


                $lotes[$i]["codigo_proveedor2"] = $ispermissionData["codigo_proveedor2"];
                $lotes[$i]["id_deposito"] = $ispermissionData["id_deposito"];
                $lotes[$i]["porcentaje_ganancia"] = $ispermissionData["porcentaje_ganancia"];
                /* $lotes[$i]["precio_base"] = $ispermissionData["precio_base"];
                $lotes[$i]["precio"] = $ispermissionData["precio"]; */
                $lotes[$i]["precio1"] = $ispermissionData["precio1"];
                $lotes[$i]["precio2"] = $ispermissionData["precio2"];
                $lotes[$i]["precio3"] = $ispermissionData["precio3"];
                $lotes[$i]["n1"] = $ispermissionData["n1"];
                $lotes[$i]["n2"] = $ispermissionData["n2"];
                $lotes[$i]["n3"] = $ispermissionData["n3"];
                $lotes[$i]["n4"] = $ispermissionData["n4"];
                $lotes[$i]["n5"] = $ispermissionData["n5"];
                $lotes[$i]["id_proveedor"] = $ispermissionData["id_proveedor"];
                $lotes[$i]["stockmin"] = $ispermissionData["stockmin"];
                $lotes[$i]["stockmax"] = $ispermissionData["stockmax"];
                
                $lotes[$i]["type_vinculo"] = $ispermissionType;

            }
        }

        return $lotes;
    }

    function getotrasopcionesalterno(Request $req) {
        $data = $req->alterno;
        $alterno = $data["codigo_proveedor"];
        $data = inventario_sucursal::with("sucursal")->where("codigo_proveedor",$alterno)->get();
        return $data;
    }
    function autovincularPedido(Request $req) {
        try {
            $id_cuenta = $req->id_cuenta;
            $num = 0;
            $numTotal = 0;
            $cuenta = cuentasporpagar::find($id_cuenta);
            if ($cuenta) {
                
                $items = cuentasporpagar_items::with("producto")->where("id_cuenta",$id_cuenta)->get();
                
                foreach ($items as $item) {
                    $match_alterno = inventario_sucursal::where("id_sucursal",$cuenta->id_sucursal)->where("codigo_proveedor",$item->producto->codigo_proveedor)->first();
                    if ($match_alterno) {
                        $match_vinculo = vinculossucursales::where("id_producto_local",$item->id_producto)
                        ->where("idinsucursal_fore",$match_alterno->idinsucursal)
                        ->where("id_sucursal_fore",$cuenta->id_sucursal)
                        ->first();
                        if (!$match_vinculo) {
                            $v = vinculossucursales::updateOrCreate([
                                "id_producto_local" => $item->id_producto, //PROD CENTRAL
                                "idinsucursal_fore" => $match_alterno->idinsucursal, //PROD SUC
                                "id_sucursal_fore" => $cuenta->id_sucursal, //SUC
                                "id_sucursal" => 13, //CENTRAL
                    
                            ],[
                                "id_producto_local" => $item->id_producto, //PROD CENTRAL
                                "id_sucursal" => 13, //CENTRAL
                                "idinsucursal_fore" => $match_alterno->idinsucursal, //PROD SUC
                                "id_sucursal_fore" => $cuenta->id_sucursal, //SUC
                                
                                "idinsucursal" => null, // INSUCURSAl, SOLO REF
                            ]);
                            $num++;
                        }
                    }
                    $numTotal++;
                }
            }
            return ["estado"=>true, "msj"=>"Éxito: $num/$numTotal VINCULOS NUEVOS"];

        } catch (\Exception $e) {
            return ["estado"=>false, "msj"=>"Error: ".$e->getMessage()];
        }

    }
    function setotrasopcionesalterno(Request $req) {
        
    }

    public function guardarProducto($arr){
        $id_factura = $arr["id_factura"];
        
        $id = null;
        if ($arr["id"]) {
            $i = inventario_sucursal::find($arr["id"]);
            if ($i) {
                if ($i->id_sucursal==13) {
                    $id = $arr["id"];
                }
            }
        }

        $arr_insert = [
            "id_sucursal" => 13,
            "codigo_barras" => $this->clean($arr["codigo_barras"],"codigo_barras"),
            "codigo_proveedor" => $this->clean($arr["codigo_proveedor"],"codigo_proveedor"),
            "descripcion" => $this->clean($arr["descripcion"],"descripcion"),
            "unidad" => $arr["unidad"],
            "id_categoria" => $arr["id_categoria"],
            "id_catgeneral" => $arr["id_catgeneral"],
            "iva" => $arr["iva"],
            "precio" => $arr["precio"],
            "precio_base" => $arr["precio_base"],
            "cantidad" => 0,
        ];
        
        if (!floatval($arr["precio"])) {unset($arr_insert["precio"]);}
        if (!floatval($arr["precio_base"])) {unset($arr_insert["precio_base"]);}

        $crearProducto = inventario_sucursal::updateOrCreate([
            "id" => $id
        ],$arr_insert); 


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
    function getEstadiscaSelectProducto(Request $req) {

        $meses = [
            "Dec" => "Dic",
            "Nov" => "Nov",
            "Oct" => "Oct",
            "Aug" => "Ago",
            "Jul" => "Jul",
            "Jun" => "Jun",
            "Sep" => "Sep",
            "May" => "May",
            "Apr" => "Abr",
            "Mar" => "Mar",
            "Feb" => "Feb",
            "Jan" => "Ene",
        ];

        $mesNum = [
            "Dic" => 12,
            "Nov" => 11,
            "Oct" => 10,
            "Ago" => 9,
            "Jul" => 8,
            "Jun" => 7,
            "Sep" => 6,
            "May" => 5,
            "Abr" => 4,
            "Mar" => 3,
            "Feb" => 2,
            "Ene" => 1,
        ];

        $today = (new NominaController)->today();
        $mesDate = date('Y-m' , strtotime($today));
        $añoDate = date('Y' , strtotime($today));

        $id = $req->id;
        $producto_master = inventario_sucursal::with("vinculados")->find($id);
        $producto_master->vinculados = $producto_master->vinculados
        ->map(function($q) use ($añoDate,$meses){
            $p = inventario_sucursal::with("sucursal")->where("id_sucursal",$q->id_sucursal_fore)->where("idinsucursal",$q->idinsucursal_fore)->first();
            $q->producto = $p;


            $estadisticas = inventario_sucursal_estadisticas::where("id_sucursal",$q->id_sucursal_fore)
            ->where("id_producto_insucursal",$q->idinsucursal_fore)
            ->where("fecha","LIKE",$añoDate."%")
            ->orderBy("fecha","desc")
            ->get();
            $anual = [];
            foreach ($estadisticas as $i => $estadistica) {
                $fecha = $estadistica["fecha"];

                $año = date('Y' , strtotime($fecha));
                $mes = $meses[date('M' , strtotime($fecha))];
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
        });

        $sumas = [];
        $totalmismoproducto = 0;
        foreach ($producto_master->vinculados as $fullname => $e) {

            foreach ($e["anual"] as $año => $databyano) {
                $totalaño = 0;
                foreach ($databyano as $mes => $ctydias) {
                    $key_anomes = $año."-".$mes;
                    $totalaño += $ctydias["ct"];

                    $sumas[$key_anomes] = isset($sumas[$key_anomes])?$sumas[$key_anomes]+$ctydias["ct"]:$ctydias["ct"];
                }
                $totalmismoproducto += $totalaño;
                $sumas[$año] = isset($sumas[$año])?$sumas[$año]+$totalaño:$totalaño;
            }
        } 
        $sumReor = [];
        foreach ($sumas as $anomes => $ct) {
            $div = explode("-",$anomes);
            array_push($sumReor,[
                "ano" => $div[0],
                "mes" => isset($div[1])?$div[1]:"",
                "ct" => $ct,
                "mesnum" => isset($div[1])?$mesNum[$div[1]]:13 
            ]);
        }
        usort($sumReor, function ($a, $b) {return $a['mesnum'] < $b['mesnum'];});
        $producto_master->sumas = $sumReor;

        return $producto_master;
    }
    function getInventarioGeneral(Request $req) {

     /*    $arr = $this->clavesinve(); */
        
        $today = (new NominaController)->today();
        $mesDate = date('Y-m' , strtotime($today));
        $añoDate = date('Y' , strtotime($today));

        $invsuc_q = $req->invsuc_q;
        $invsuc_num = $req->invsuc_num;
        $invsuc_orderBy = $req->invsuc_orderBy;
        $inventarioGeneralqsucursal = $req->inventarioGeneralqsucursal;

        /* $camposAgregadosBusquedaEstadisticas = $req->camposAgregadosBusquedaEstadisticas;
        $sucursalesAgregadasBusquedaEstadisticas = !count($req->sucursalesAgregadasBusquedaEstadisticas)? [] :$req->sucursalesAgregadasBusquedaEstadisticas->map(function($q) {
            return $q["id"]; 
        });


        $estadisticas = []; */
        
        //foreach ($this->clavesinve() as $i => $val) {
            //$n1 = $val[1];
            //$n2 = $val[3];
            //$n3 = $val[5];
            //$n4 = $val[7];
            //$n5 = $val[9];
//            
            //$merge = inventario_sucursal::with(["sucursal"])
            //->when($sucursalesAgregadasBusquedaEstadisticas,function($q) use($sucursalesAgregadasBusquedaEstadisticas) {
                //$q->whereIn("id_sucursal",$sucursalesAgregadasBusquedaEstadisticas);
            //})
            //->where(function($q) use ($n1,$n2,$n3,$n4,$n5) {
                //if ($n1) {
                    //$q->where("n1",$n1);
//                }
                //if ($n2) {
                    //$q->where("n2",$n2);
//                }
                //if ($n3) {
                    //$q->where("n3",$n3);
//                }
                //if ($n4) {
                    //$q->where("n4",$n4);
//                }
                //if ($n5) {
                    //$q->where("n5",$n5);
//                }
            //})
            ///* ->when($camposAgregadosBusquedaEstadisticas,function($q) use($camposAgregadosBusquedaEstadisticas) {
                //foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                    //$q->where($e["campo"], $e["valor"]);
//                }
            //}) */
           ///*  ->limit($invsuc_num) */
            //->orderBy("n1","desc")
            //->orderBy("id_sucursal","asc")
            //->orderBy("descripcion","asc")
            //->get()
            //->map(function($q) use ($today,$mesDate,$añoDate, $camposAgregadosBusquedaEstadisticas,$n1,$n2,$n3,$n4,$n5){
                //$nombrefull = "";
//    
                ///* foreach ($camposAgregadosBusquedaEstadisticas as $i => $e) {
                    //$nombrefull .= ($q[$e["campo"]]?($q[$e["campo"]]." "):"");
                    //} */
                //if ($n1) {
                    //$nombrefull .=  $q["n1"]? ($q["n1"]." "): "";
//                }
                //if ($n2) {
                    //$nombrefull .=  $q["n2"]? ($q["n2"]." "): "";
//                }
                //if ($n3) {
                    //$nombrefull .=  $q["n3"]? ($q["n3"]." "): "";
//                }
                //if ($n4) {
                    //$nombrefull .=  $q["n4"]? ($q["n4"]." "): "";
//                }
                //if ($n5) {
                    //$nombrefull .=  $q["n5"]? ($q["n5"]." "): "";
//                }
//                
                //$q->nombrefull = $nombrefull? $nombrefull: "SIN ESPECIFICAR"; 
//        
                //$estadisticas = inventario_sucursal_estadisticas::where("id_sucursal",$q->id_sucursal)
                //->where("id_producto_insucursal",$q->idinsucursal)
                //->where("fecha","LIKE",$añoDate."%")
                //->orderBy("fecha","desc")
                //->get();
                //$anual = [];
                //foreach ($estadisticas as $i => $estadistica) {
                    //$fecha = $estadistica["fecha"];
//    
                    //$año = date('Y' , strtotime($fecha));
                    //$mes = date('M' , strtotime($fecha));
                    //$ct = $estadistica["cantidad"];
//    
//    
                    //if (!array_key_exists($año, $anual)) {
                        //$anual[$año][$mes] = [
                            //"ct" => $ct,
                            //"dias" => 1
                        //];
                    //}else{
                        //if (array_key_exists($mes,$anual[$año])) {
                            //$anual[$año][$mes] = [
                                //"ct" => $anual[$año][$mes]["ct"]+$ct,
                                //"dias" => $anual[$año][$mes]["dias"]+1,
                            //];
                        //}else{
                            //$anual[$año][$mes] = [
                                //"ct" => $ct,
                                //"dias" => 1,
                            //];
//                        }
//                    }
//    
//                }
                //$q->anual = $anual;
//                
                //return $q;
            //}) ;
            ////$estadisticas = $estadisticas->merge($merge);
            //$estadisticas = array_merge($estadisticas,$merge->toArray());
//        }
//        
        //$estadisticas = collect($estadisticas)->groupBy(["nombrefull","sucursal.codigo"]);



        $sumas = [];
        
        /* foreach ($estadisticas as $fullname => $byscursales) {
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

        } */

        $estadisticas = inventario_sucursal::with(["sucursal","vinculados"=>function($q) {
            $q->orderBy("id_sucursal_fore","asc");
        }])
        ->when($invsuc_q, function($q) use ($invsuc_q) {
            $q->where(function($q) use ($invsuc_q) {
                $q->orwhere("codigo_barras","LIKE","%$invsuc_q%")
                ->orwhere("codigo_proveedor","LIKE","%$invsuc_q%")
                ->orwhere("descripcion","LIKE","%$invsuc_q%");
            });
        })
        ->where("id_sucursal",13)
        ->limit($invsuc_num)
        ->get()
        ->map(function($q) {
            $q->vinculados = $q->vinculados
            ->map(function($q) {
                $q->producto = inventario_sucursal::with("sucursal")->where("id_sucursal",$q->id_sucursal_fore)->where("idinsucursal",$q->idinsucursal_fore)->first();
                return $q;
            });
            return $q;
        });


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
