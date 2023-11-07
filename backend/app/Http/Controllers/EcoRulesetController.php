<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcoRulesetRequest;
use App\Http\Resources\EcoRulesetResource;
use App\Models\EcoRuleset;
use Illuminate\Http\Request;

class EcoRulesetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ecoRulesets = EcoRuleset::when($request->get('duty_year'), function ($query) use ($request) {
            $query->where('duty_year', $request->duty_year);
        })->when($request->get('location'), function ($query) use ($request) {
            $query->whereHas('ecoSystem', function ($ecoSystemQuery) use ($request) {
                $ecoSystemQuery->where('location', $request->location);
            });
        })->with('ecoSystem')->paginate()->withQueryString();

        return EcoRulesetResource::collection($ecoRulesets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EcoRulesetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EcoRulesetRequest $request)
    {
        $ecoRuleset = EcoRuleset::create($request->validated());

        return new EcoRulesetResource($ecoRuleset);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcoRuleset  $ecoRuleset
     * @return \Illuminate\Http\Response
     */
    public function show(EcoRuleset $ecoRuleset)
    {
        return new EcoRulesetResource($ecoRuleset);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EcoRulesetRequest  $request
     * @param  \App\Models\EcoRuleset  $ecoRuleset
     * @return \Illuminate\Http\Response
     */
    public function update(EcoRulesetRequest $request, EcoRuleset $ecoRuleset)
    {
        $ecoRuleset->update($request->validated());

        return new EcoRulesetResource($ecoRuleset);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcoRuleset  $ecoRuleset
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoRuleset $ecoRuleset)
    {
        $ecoRuleset->delete();

        return response()->noContent();
    }
}
