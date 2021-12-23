<?php

namespace App\Http\Controllers;

use App\Models\fallas;
use App\Models\sucursal;
use App\Http\Requests\StorefallasRequest;
use App\Http\Requests\UpdatefallasRequest;
use Illuminate\Http\Request;
use Response;


class FallasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setFallas(Request $req)
    {

        $sucursal = sucursal::where("codigo",$req->sucursal_code)->first();

        if (!$sucursal) {
            return Response::json([
                "msj"=>"No se encontró sucursal",
                "estado"=>false
            ]);
        }

        $fallas = $req->fallas;
        // return Response::json(["msj"=>$fallas,"estado"=>true]);
        foreach ($fallas as $val) {
            // code...
            fallas::UpdateOrCreate([
                "id_producto"=>$val["id_producto"],
                "id_sucursal"=>$sucursal->id,
            ],[

                "cantidad"=>$val["cantidad"],
                "id_producto"=>$val["id_producto"],
                "id_sucursal"=>$sucursal->id,
            ]);
        }
        return Response::json(["msj"=>"Éxito","estado"=>true]);
    }

    public function getFallas(Request $req)
    {
        return fallas::with("producto")->where("id_sucursal",$req->id_sucursal)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorefallasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorefallasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\fallas  $fallas
     * @return \Illuminate\Http\Response
     */
    public function show(fallas $fallas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\fallas  $fallas
     * @return \Illuminate\Http\Response
     */
    public function edit(fallas $fallas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatefallasRequest  $request
     * @param  \App\Models\fallas  $fallas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatefallasRequest $request, fallas $fallas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\fallas  $fallas
     * @return \Illuminate\Http\Response
     */
    public function destroy(fallas $fallas)
    {
        //
    }
}
