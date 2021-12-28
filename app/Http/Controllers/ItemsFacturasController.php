<?php

namespace App\Http\Controllers;

use App\Models\items_facturas;
use App\Models\inventario;
use App\Http\Requests\Storeitems_facturasRequest;
use App\Http\Requests\Updateitems_facturasRequest;

use Illuminate\Http\Request;
use Response;

class ItemsFacturasController extends Controller
{
    public function delItemFact(Request $req)
    {
        try {
            $id = $req->id;
            $items_factura = items_facturas::find($id);
            $inv = inventario::find($items_factura->id_producto);
            $inv->cantidad = $inv->cantidad - ($items_factura->cantidad);
            if ($inv->save()) {
                $items_factura->delete();
                return Response::json(["msj"=>"Éxito al eliminar","estado"=>true]);
            }


            
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
            
        }
    }
}
