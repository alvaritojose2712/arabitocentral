<?php

namespace App\Http\Controllers;

use App\Models\proveedores;
use App\Http\Requests\StoreproveedoresRequest;
use App\Http\Requests\UpdateproveedoresRequest;

class ProveedoresController extends Controller
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
     * @param  \App\Http\Requests\StoreproveedoresRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreproveedoresRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function show(proveedores $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function edit(proveedores $proveedores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproveedoresRequest  $request
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateproveedoresRequest $request, proveedores $proveedores)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function destroy(proveedores $proveedores)
    {
        //
    }
}
