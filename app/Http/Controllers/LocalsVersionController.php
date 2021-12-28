<?php

namespace App\Http\Controllers;

use App\Models\locals_version;
use App\Http\Requests\Storelocals_versionRequest;
use App\Http\Requests\Updatelocals_versionRequest;

class LocalsVersionController extends Controller
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
     * @param  \App\Http\Requests\Storelocals_versionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storelocals_versionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\locals_version  $locals_version
     * @return \Illuminate\Http\Response
     */
    public function show(locals_version $locals_version)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\locals_version  $locals_version
     * @return \Illuminate\Http\Response
     */
    public function edit(locals_version $locals_version)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatelocals_versionRequest  $request
     * @param  \App\Models\locals_version  $locals_version
     * @return \Illuminate\Http\Response
     */
    public function update(Updatelocals_versionRequest $request, locals_version $locals_version)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\locals_version  $locals_version
     * @return \Illuminate\Http\Response
     */
    public function destroy(locals_version $locals_version)
    {
        //
    }
}
