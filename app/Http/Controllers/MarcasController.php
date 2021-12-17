<?php

namespace App\Http\Controllers;

use App\Models\marcas;
use App\Http\Requests\StoremarcasRequest;
use App\Http\Requests\UpdatemarcasRequest;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremarcasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoremarcasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function show(marcas $marcas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function edit(marcas $marcas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemarcasRequest  $request
     * @param  \App\Models\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatemarcasRequest $request, marcas $marcas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\marcas  $marcas
     * @return \Illuminate\Http\Response
     */
    public function destroy(marcas $marcas)
    {
        //
    }
}
