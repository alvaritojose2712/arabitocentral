<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar_items;
use App\Models\cuentasporpagar;
use App\Http\Requests\Storecuentasporpagar_itemsRequest;
use App\Http\Requests\Updatecuentasporpagar_itemsRequest;
use App\Models\inventario;
use App\Models\inventario_sucursal;
use Illuminate\Http\Request;
use Response;


class CuentasporpagarItemsController extends Controller
{
    function modItemFact(Request $req) {
        $id = $req->id;
        $campo = $req->campo;
        $valor = floatval($req->valor);

        $cxpItem = cuentasporpagar_items::find($id);
        if ($cxpItem) {
            $cxp = cuentasporpagar::find($cxpItem->id_cuenta);
            if ($cxp->aprobado==1) {
                return Response::json(["msj"=>"Error: Cuenta ya aprobada, no se puede modificar","estado"=>0]);
            }else{

                $inventario = inventario_sucursal::find($cxpItem->id_producto);
                
                switch ($campo) {
                    case 'cantidad':
                        $cxpItem->cantidad = $valor;
                        break;
                    case 'basef':
                        $cxpItem->basef = $valor;
                        break;
                    case 'base':
                        $cxpItem->base = $valor;
                        $inventario->base = $valor;
                        break;
                    case 'venta':
                        $cxpItem->venta = $valor;
                        $inventario->venta = $valor;
                    break;
                }
                $inventario->save();
                $cxpItem->save();
                return Response::json(["msj"=>"Éxito al eliminar","estado"=>1]);
            }

        }
    }
    function delItemFact(Request $req) {
        $id = $req->id;
        $cxpItem = cuentasporpagar_items::find($id);
        if ($cxpItem) {
            $cxp = cuentasporpagar::find($cxpItem->id_cuenta);
            if ($cxp->aprobado==1) {
                return Response::json(["msj"=>"Error: Cuenta ya aprobada, no se puede eliminar","estado"=>0]);
            }else{
                if ($cxpItem->delete()) {
                    return Response::json(["msj"=>"Éxito al eliminar","estado"=>1]);
                }
            }

        }
    }
}
