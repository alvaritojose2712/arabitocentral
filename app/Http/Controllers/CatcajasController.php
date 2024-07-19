<?php

namespace App\Http\Controllers;

use App\Models\catcajas;
use App\Http\Requests\StorecatcajasRequest;
use App\Http\Requests\UpdatecatcajasRequest;

class CatcajasController extends Controller
{
    function getCatCajas(){
        return catcajas::orderBy("nombre","asc")->get();
    }
}
