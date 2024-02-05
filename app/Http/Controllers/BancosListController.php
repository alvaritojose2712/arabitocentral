<?php

namespace App\Http\Controllers;

use App\Models\bancos_list;
use App\Http\Requests\Storebancos_listRequest;
use App\Http\Requests\Updatebancos_listRequest;

class BancosListController extends Controller
{
    function getMetodosPago(){
        return bancos_list::all();
    }
}
