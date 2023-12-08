<?php

namespace App\Http\Controllers;

use App\Models\productoxproveedor;
use App\Http\Requests\StoreproductoxproveedorRequest;
use App\Http\Requests\UpdateproductoxproveedorRequest;

use Illuminate\Http\Request;
use Response;

class ProductoxproveedorController extends Controller
{
   public function selectPrecioxProveedorSave(Request $req){
    $productoxproveedor = new productoxproveedor();
    $productoxproveedor->id_proveedor = $req->id_proveedor;
    $productoxproveedor->id_producto = $req->id_producto;
    $productoxproveedor->precio = $req->precio?$req->precio:0;
    $productoxproveedor->save();
   } 
   public function getPrecioxProveedor(Request $req){
    $id_producto = $req->id_producto;
    return productoxproveedor::with([
      "producto" => function($q) {
         $q->with(["marca","categoria","catgeneral"]);
      },
      "proveedor"
      ])->where("id_producto",$id_producto)->get();
   }
}
