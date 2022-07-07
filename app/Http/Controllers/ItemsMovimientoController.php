<?php

namespace App\Http\Controllers;

use App\Models\items_movimiento;
use App\Http\Requests\Storeitems_movimientoRequest;
use App\Http\Requests\Updateitems_movimientoRequest;

class ItemsMovimientoController extends Controller
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
     * @param  \App\Http\Requests\Storeitems_movimientoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeitems_movimientoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\items_movimiento  $items_movimiento
     * @return \Illuminate\Http\Response
     */
    public function show(items_movimiento $items_movimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\items_movimiento  $items_movimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(items_movimiento $items_movimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateitems_movimientoRequest  $request
     * @param  \App\Models\items_movimiento  $items_movimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Updateitems_movimientoRequest $request, items_movimiento $items_movimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\items_movimiento  $items_movimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(items_movimiento $items_movimiento)
    {
        //
    }
}
