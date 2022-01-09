<?php

namespace App\Http\Controllers;

use App\Models\locals_version;
use App\Http\Requests\Storelocals_versionRequest;
use App\Http\Requests\Updatelocals_versionRequest;

class LocalsVersionController extends Controller
{
    public function getVersion()
    {
        $version = locals_version::orderBy("id","desc")->first();

        return $version->version;
    }
}
