<?php

namespace App\Http\Controllers;

use App\Models\deposito;
use App\Http\Requests\StoredepositoRequest;
use App\Http\Requests\UpdatedepositoRequest;

class DepositoController extends Controller
{
    public function getDepositos()
    {
        return deposito::all();
    }
}
