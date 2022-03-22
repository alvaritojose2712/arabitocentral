<?php

namespace App\Http\Controllers;

use App\Models\lotes;
use App\Http\Requests\StorelotesRequest;
use App\Http\Requests\UpdatelotesRequest;

class LotesController extends Controller
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
     * @param  \App\Http\Requests\StorelotesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorelotesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function show(lotes $lotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function edit(lotes $lotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatelotesRequest  $request
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatelotesRequest $request, lotes $lotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\lotes  $lotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(lotes $lotes)
    {
        //
    }
}
