<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\nominapagos;
use App\Http\Requests\StorenominapagosRequest;
use App\Http\Requests\UpdatenominapagosRequest;
use Response;
class NominapagosController extends Controller
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
     * @param  \App\Http\Requests\StorenominapagosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorenominapagosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\nominapagos  $nominapagos
     * @return \Illuminate\Http\Response
     */
    public function show(nominapagos $nominapagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\nominapagos  $nominapagos
     * @return \Illuminate\Http\Response
     */
    public function edit(nominapagos $nominapagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatenominapagosRequest  $request
     * @param  \App\Models\nominapagos  $nominapagos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatenominapagosRequest $request, nominapagos $nominapagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\nominapagos  $nominapagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(nominapagos $nominapagos)
    {
        //
    }
}
