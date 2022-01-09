<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\facturas;
use App\Models\items_facturas;
use App\Models\moneda;


use App\Http\Requests\StoreinventarioRequest;
use App\Http\Requests\UpdateinventarioRequest;
use Illuminate\Http\Request;
use Response;


class InventarioController extends Controller
{
    public function index(Request $req)
    {
        $exacto = false;

        if (isset($req->exacto)) {
            if ($req->exacto=="si") {
                $exacto = true;
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
            $data = inventario::limit(20)
            ->orderBy($orderColumn,$orderBy)
            ->get();
        }else{
            $data = inventario::with([
                "proveedor",
                "categoria",
                "deposito",
            ])
            ->where(function($e) use($itemCero,$q,$exacto){

                if ($exacto) {
                    $e->orWhere("descripcion","$q")
                    ->orWhere("codigo_proveedor","$q");
                }else{
                    $e->orWhere("descripcion","LIKE","%$q%")
                    ->orWhere("codigo_proveedor","LIKE","$q%")
                    ->orWhere("codigo_barras","LIKE","$q%");

                }

            })
            ->limit($num)
            ->orderBy($orderColumn,$orderBy)
            ->get();
        }

        $data->map(function($q) use ($bs,$cop)
        {
            $q->bs = number_format($q->precio*$bs["valor"],2,".",",");
            $q->cop = number_format($q->precio*$cop["valor"],2,".",",");
            return $q;
        });
       
        return $data; 
    }

    public function guardarNuevoProducto(Request $req)
    {   

        $id_factura = $req->id_factura;
        $find_factura = facturas::find($id_factura);

        $ctInsert = $req->inpInvcantidad;

        if (!$find_factura) {
           return Response::json(["msj"=>"Error: No hay factura seleccionada","estado"=>false]);
        }
         try {
            
            $tipo = "";
            if (!$req->id) {
                $ctNew = $ctInsert;
                $tipo = "Nuevo";
            }else{
                $before = inventario::find($req->id);

                if ($before) {
                    $beforecantidad = $before->cantidad;
                    $ctNew = $ctInsert - $beforecantidad;
                    $tipo = "Actualización";
                }
            }

            $insertOrUpdateInv = inventario::updateOrCreate([
                "id" => $req->id
            ],[
                "codigo_barras" => $req->inpInvbarras,
                "cantidad" => $ctInsert,
                "codigo_proveedor" => $req->inpInvalterno,
                "unidad" => $req->inpInvunidad,
                "id_categoria" => $req->inpInvcategoria,
                "descripcion" => $req->inpInvdescripcion,
                "precio_base" => $req->inpInvbase,
                "precio" => $req->inpInvventa,
                "iva" => $req->inpInviva,
                "id_proveedor" => $req->inpInvid_proveedor,
                "id_marca" => $req->inpInvid_marca,
                "id_deposito" => $req->inpInvid_deposito,
                "porcentaje_ganancia" => $req->inpInvporcentaje_ganancia
            ]);

            // $this->checkFalla($req->id,$ctInsert);

            if($insertOrUpdateInv){

                $id_pro = $insertOrUpdateInv->id;
                $check_fact = items_facturas::where("id_factura",$id_factura)->where("id_producto",$id_pro)->first();

                if ($check_fact) {
                    $ctNew = $ctInsert - ($beforecantidad - $check_fact->cantidad);
                }


                if ($ctNew==0) {
                    items_facturas::where("id_factura",$id_factura)->where("id_producto",$id_pro)->delete();
                }else{
                    items_facturas::updateOrCreate([
                        "id_factura" => $id_factura,
                        "id_producto" => $id_pro,
                    ],[
                        "cantidad" => $ctNew,
                        "tipo" => $tipo,

                    ]);

                }

            }

            return Response::json(["msj"=>"Éxito","estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        } 
    }
    public function delProducto(Request $req)
    {
        $id = $req->id;
        try {
            inventario::find($id)->delete();
            return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);   
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error al eliminar. ".$e->getMessage(),"estado"=>false]);
            
        }  
    }

    public function checkFalla($id,$ct)
    {   
        if ($ct>1) {
            $f = fallas::where("id_producto",$id);
            if ($f) {
                $f->delete();
            }
        }else if($ct<=1){

            fallas::updateOrCreate(["id_producto"=>$id],["id_producto"=>$id]);
        }
    }
}
