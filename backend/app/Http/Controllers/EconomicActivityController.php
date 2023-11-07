<?php

namespace App\Http\Controllers;

use App\Http\Resources\EconomicActivityResource;
use App\Models\EconomicActivity;
use Illuminate\Http\Request;

class EconomicActivityController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $economicActivities = EconomicActivity::paginate();
        
        return EconomicActivityResource::collection($economicActivities);
    }
}
