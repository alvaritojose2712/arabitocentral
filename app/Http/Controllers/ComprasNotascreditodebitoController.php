<?php

namespace App\Http\Controllers;

use App\Models\compras_notascreditodebito;
use App\Http\Requests\Storecompras_notascreditodebitoRequest;
use App\Http\Requests\Updatecompras_notascreditodebitoRequest;

use Illuminate\Http\Request;
use Response;

class ComprasNotascreditodebitoController extends Controller
{
    function getNovedadesPedidosData(Request $req)  {
        $data = compras_notascreditodebito::all();
        return $data;
    }
}
