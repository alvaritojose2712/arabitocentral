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
        return sucursal::all();
    }
}
