<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\inventario_sucursal;
use App\Models\sucursal;
use App\Models\categorias;
use App\Models\proveedores;
use App\Models\moneda;
use App\Models\tareas;
use App\Models\inventario;


use App\Http\Requests\Storeinventario_sucursalRequest;
use App\Http\Requests\Updateinventario_sucursalRequest;
use Response;



class InventarioSucursalController extends Controller
{
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

    function setInventarioSucursalFun($arr) {
        return inventario_sucursal::updateOrCreate([
            "id_sucursal" => $arr["id_sucursal"],
            "id_producto" => $arr["id_producto"],
        ],[
            "cantidad" => $arr["cantidad"],
        ]);
    }


    public function sendInventarioCt(Request $req) {
        try {
            $inventariodeldia =  $req->inventario;
            $codigo_origen =  $req->codigo_origen;

            $id_ruta = $this->retOrigenDestino($codigo_origen,$codigo_origen);
            $id_origen = $id_ruta["id_origen"];

            $num = 0;

            foreach ($inventariodeldia as $i => $producto) {
                $id_vinculacion = $producto["id_vinculacion"];
                if (inventario::find($id_vinculacion)) {

                    $insert = $this->setInventarioSucursalFun([
                        "id_sucursal" => $id_origen,
                        "id_producto" =>  $id_vinculacion,
                        "cantidad" => $producto["cantidad"],
                    ]);

                    if ($insert) {
                        $num++;
                    }
                }
            }

            return $num." productos actualizados de ".count($inventariodeldia);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    
}
