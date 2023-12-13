<?php

namespace App\Http\Controllers;

use App\Models\catcajas;
use App\Http\Requests\StorecatcajasRequest;
use App\Http\Requests\UpdatecatcajasRequest;

class CatcajasController extends Controller
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
     * @param  \App\Http\Requests\StorecatcajasRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecatcajasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\catcajas  $catcajas
     * @return \Illuminate\Http\Response
     */
    public function show(catcajas $catcajas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\catcajas  $catcajas
     * @return \Illuminate\Http\Response
     */
    public function edit(catcajas $catcajas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecatcajasRequest  $request
     * @param  \App\Models\catcajas  $catcajas
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecatcajasRequest $request, catcajas $catcajas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\catcajas  $catcajas
     * @return \Illuminate\Http\Response
     */
    public function destroy(catcajas $catcajas)
    {
        //
    }
}
