<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\NuevaTarea;

class sockets extends Controller
{
    public function setNuevaTareaCentral(Request $req)
    {
    	$id = 12;

    	event(new NuevaTarea($id));

    }
}
