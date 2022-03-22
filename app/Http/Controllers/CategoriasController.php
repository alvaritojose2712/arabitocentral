<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Http\Requests\StorecategoriasRequest;
use App\Http\Requests\UpdatecategoriasRequest;

class CategoriasController extends Controller
{
    public function getCategorias()
    {
        return categorias::all();
    }
}
