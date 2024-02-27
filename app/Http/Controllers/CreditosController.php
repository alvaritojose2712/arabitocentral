<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\creditos;
use App\Http\Requests\StorecreditosRequest;
use App\Http\Requests\UpdatecreditosRequest;

class CreditosController extends Controller
{
   function sendCreditos($creditos,$id_sucursal) {
    try {
        creditos::where("id_sucursal",$id_sucursal)->delete();
        $num = 0;
        foreach ($creditos as $e) {
    
            $c = str_replace(["-", ".", "v", "V", " "], ["","","","",""], $e["identificacion"]);        

            $id_cliente = clientes::updateOrCreate([
                "identificacion" => $c,
            ],[
                "identificacion" => $c,
                "nombre" => $e["nombre"],
                "correo" => $e["correo"],
                "direccion" => $e["direccion"],
                "telefono" => $e["telefono"],
                "estado" => $e["estado"],
                "ciudad" => $e["ciudad"],
            ]);
    
    
            if ($id_cliente) {
                creditos::updateOrCreate([
                    "id_cliente" => $id_cliente->id,
                    "id_sucursal" => $id_sucursal,
                ],[
                    "id_cliente" => $id_cliente->id,
                    "id_sucursal" => $id_sucursal,
                    "saldo" => $e["saldo"],
                ]);
    
                $num++;
            }
        }
        return [
            "last" => true,
            "msj" => "OK CREDITOS $num / ".count($creditos),
        ];
        
    } catch (\Exception $e) {
        return "Error Creditos TRY CENTRAL: " . $e->getMessage()." ".$e->getLine();

    }
   }
}
