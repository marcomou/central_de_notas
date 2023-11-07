<?php

namespace App\Http\Controllers;

use App\Http\Resources\LegalTypeResource;
use App\Models\LegalType;
use Illuminate\Http\Request;

class LegalTypeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $legalTypes = LegalType::all();

        return LegalTypeResource::collection($legalTypes);
    }
}
