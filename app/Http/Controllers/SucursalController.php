<?php

namespace App\Http\Controllers;

use App\Models\sucursal;
use App\Http\Requests\StoresucursalRequest;
use App\Http\Requests\UpdatesucursalRequest;
use Illuminate\Http\Request;

use Response;

class SucursalController extends Controller
{
    public function getSucursales(Request $req)
    {
        $q = isset($req->q) ? $req->q :"";
        if ($q) {
            return sucursal::orwhere("codigo","LIKE","%$q%")->orwhere("nombre","LIKE","%$q%")
            ->orderByRaw("FIELD(id,1,2,5,4,3,6,7,15,8,9,10,11,16,12,14,18,17,13)")
            ->get();
        }else{
            return sucursal::orderByRaw("FIELD(id,1,2,5,4,3,6,7,15,8,9,10,11,16,12,14,18,17,13)")->get();
        }
    }
}
