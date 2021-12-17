<?php

namespace App\Http\Controllers;

use App\Models\ct_sucursal;
use App\Http\Requests\Storect_sucursalRequest;
use App\Http\Requests\Updatect_sucursalRequest;

class CtSucursalController extends Controller
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
     * @param  \App\Http\Requests\Storect_sucursalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storect_sucursalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ct_sucursal  $ct_sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(ct_sucursal $ct_sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ct_sucursal  $ct_sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(ct_sucursal $ct_sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatect_sucursalRequest  $request
     * @param  \App\Models\ct_sucursal  $ct_sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(Updatect_sucursalRequest $request, ct_sucursal $ct_sucursal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ct_sucursal  $ct_sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(ct_sucursal $ct_sucursal)
    {
        //
    }
}
