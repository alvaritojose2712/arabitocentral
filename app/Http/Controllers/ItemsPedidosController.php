<?php

namespace App\Http\Controllers;

use App\Models\items_pedidos;
use App\Http\Requests\Storeitems_pedidosRequest;
use App\Http\Requests\Updateitems_pedidosRequest;

class ItemsPedidosController extends Controller
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
     * @param  \App\Http\Requests\Storeitems_pedidosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeitems_pedidosRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\items_pedidos  $items_pedidos
     * @return \Illuminate\Http\Response
     */
    public function show(items_pedidos $items_pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\items_pedidos  $items_pedidos
     * @return \Illuminate\Http\Response
     */
    public function edit(items_pedidos $items_pedidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateitems_pedidosRequest  $request
     * @param  \App\Models\items_pedidos  $items_pedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Updateitems_pedidosRequest $request, items_pedidos $items_pedidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\items_pedidos  $items_pedidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(items_pedidos $items_pedidos)
    {
        //
    }
}
