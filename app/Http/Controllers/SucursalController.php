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
            return sucursal::orwhere("codigo","LIKE","%$q%")->orwhere("nombre","LIKE","%$q%")->get();
        }else{
            return sucursal::all();
        }
    }
}
