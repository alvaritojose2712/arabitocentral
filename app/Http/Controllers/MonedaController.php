<?php

namespace App\Http\Controllers;

use App\Models\moneda;
use App\Http\Requests\StoremonedaRequest;
use App\Http\Requests\UpdatemonedaRequest;

class MonedaController extends Controller
{
    public function getMoneda()
    {
        $cop = moneda::where("tipo",2)->orderBy("id","desc")->first();
        $bs = moneda::where("tipo",1)->orderBy("id","desc")->first();

        return [
            "cop"=>$cop["valor"], 
            "bs"=>$bs["valor"]
        ];
    }
}
