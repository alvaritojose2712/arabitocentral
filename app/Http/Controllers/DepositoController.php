<?php

namespace App\Http\Controllers;

use App\Models\deposito;
use App\Http\Requests\StoredepositoRequest;
use App\Http\Requests\UpdatedepositoRequest;

class DepositoController extends Controller
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
     * @param  \App\Http\Requests\StoredepositoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoredepositoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function show(deposito $deposito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function edit(deposito $deposito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatedepositoRequest  $request
     * @param  \App\Models\deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatedepositoRequest $request, deposito $deposito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\deposito  $deposito
     * @return \Illuminate\Http\Response
     */
    public function destroy(deposito $deposito)
    {
        //
    }
}
