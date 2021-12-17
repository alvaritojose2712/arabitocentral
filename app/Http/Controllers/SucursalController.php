<?php

namespace App\Http\Controllers;

use App\Models\sucursal;
use App\Http\Requests\StoresucursalRequest;
use App\Http\Requests\UpdatesucursalRequest;

class SucursalController extends Controller
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
     * @param  \App\Http\Requests\StoresucursalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresucursalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function show(sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function edit(sucursal $sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatesucursalRequest  $request
     * @param  \App\Models\sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesucursalRequest $request, sucursal $sucursal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sucursal  $sucursal
     * @return \Illuminate\Http\Response
     */
    public function destroy(sucursal $sucursal)
    {
        //
    }
}
