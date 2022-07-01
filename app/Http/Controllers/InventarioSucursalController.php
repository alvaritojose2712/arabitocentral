<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\inventario_sucursal;
use App\Models\sucursal;
use App\Models\categorias;
use App\Models\proveedores;

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
        if (isset($req["categorias"])) {
                foreach ($req["categorias"] as $e) {
                    categorias::updateOrCreate(
                    [
                        "id"=>$e["id"]
                    ],[
                        "descripcion"=>$e["descripcion"],
                    ]);
                }

            }
            if (isset($req["proveedores"])) {
                foreach ($req["proveedores"] as $e) {
                    proveedores::updateOrCreate(
                        [
                            "id" => $e["id"],
                        ],[
                            "descripcion" => $e["descripcion"],
                            "rif" => $e["rif"],
                            "direccion" => $e["direccion"],
                            "telefono" => $e["telefono"],
                        ]
                    );
                }
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
                        "id_categoria" => $e["id_categoria"],
                        "descripcion" => $e["descripcion"],
                        "precio_base" => $e["precio_base"],
                        "precio" => $e["precio"],
                        "iva" => $e["iva"],
                        "id_proveedor" => $e["id_proveedor"],
                        "id_marca" => $e["id_marca"],
                        "id_deposito" => $e["id_deposito"],
                        "porcentaje_ganancia" => $e["porcentaje_ganancia"]
                    ]); 
                    if ($insertOrUpdateInv) {
                         $count++;
                     } 

                }
                if ($insertOrUpdateInv) {
                    return Response::json(["estado"=>true,"msj"=>"Desde Central: Exporttación exitosa. Sucursal Code: ".$sucursal->codigo." | $count/".count($req["inventario"])." productos exitosos"]);
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
    public function getInventarioSucursalFromCentral(Request $req)
    {   
        $type = $req->type;
        $id = $req->id;

        switch ($type) {
            case 'inventariSucursalFromCentral':
                return inventario_sucursal::with(["proveedor","categoria"])
                ->where("id_sucursal",$req->id)
                ->get();
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
                return [];
                break;
            
            
        }
    }

    
}
