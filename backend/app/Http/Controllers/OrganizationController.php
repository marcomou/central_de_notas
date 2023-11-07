<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\EcoDutyResource;
use App\Http\Resources\EcoSystemResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use App\Models\EcoDuty;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::paginate();

        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Organization\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $organization = Organization::create($request->validated());

        return new OrganizationResource($organization);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Organization\UpdateRequest  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return new OrganizationResource($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return response()->noContent();
    }

    /**
     * List organization's users.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function users(Organization $organization)
    {
        $organization->load('users');

        return UserResource::collection($organization->users);
    }

    /**
     * Attach user on organization.
     *
     * @param  \App\Http\Requests\OrganizationUserRequest  $request
     * @param  \App\Models\Organization  $organization
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function attachUser(Organization $organization, User $user)
    {
        $organization->users()->attach($user);

        $organization->load('users');

        return UserResource::collection($organization->users);
    }

    /**
     * Attach user on organization.
     *
     * @param  \App\Models\Organization  $organization
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function detachUser(Organization $organization, User $user)
    {
        $organization->users()->detach($user);

        $organization->load('users');

        return UserResource::collection($organization->users);
    }

    /**
     * List organization's addresses.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function addresses(Organization $organization)
    {
        $organization->load('addresses');

        return AddressResource::collection($organization->addresses);
    }

    /**
     * Show organization's primary address.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function primaryAddress(Organization $organization)
    {
        $organization->load('primaryAddress');

        return new AddressResource($organization->primaryAddress);
    }

    /**
     * Show organization's fiscal address.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function fiscalAddress(Organization $organization)
    {
        $organization->load('fiscalAddress');

        return new AddressResource($organization->fiscalAddress);
    }

    public function ecoDuties(Request $request, Organization $organization)
    {
        $q = $request->get('q');
        $year = $request->get('year');
        $location = $request->get('location');

        $ecoDuties = EcoDuty::query()
            ->when($year, function ($query) use ($year) {
                $query->whereHas('ecoRuleset', function ($query) use ($year) {
                    $query->where("duty_year", "$year");
                });
            })
            ->when($location, function ($query) use ($location) {
                $query->whereHas('ecoRuleset.ecoSystem.location', function ($query) use ($location) {
                    $query->where("acronym", "$location");
                });
            })

            ->when($q, function ($query) use ($q, $organization) {
                $q = preg_replace('/\s+/', '%', trim($q));
                $query->whereHas('managingOrganization', function ($query) use ($q) {
                    $query->where("federal_registration", "like", "%$q%")
                        ->orWhere("legal_name", "like", "%$q%");
                })->orWhere('managing_code', 'like', "%$q%");
            })
            ->when($location, function ($query) use ($location) {
                $query->whereHas('ecoRuleset.ecoSystem.location', function ($query) use ($location) {
                    $query->where("acronym", "$location");
                });
            });

        if ($organization->isFederalOrganization() || $organization->isSupervisingOrganization())
            $ecoDuties->with('managingOrganization');

        if ($organization->isManagingOrganization()) {
            $ecoDuties->where('managing_organization_id', $organization->id);
        }

        if ($organization->isSupervisingOrganization()) {
            $ecoDuties->whereIn('eco_ruleset_id', $organization->ecoRulesets->modelKeys());
        }

        return EcoDutyResource::collection($ecoDuties->paginate()->withQueryString());
    }

    public function ecoSystems(Organization $organization)
    {
        $organization->load('ecoSystems');

        return EcoSystemResource::collection($organization->ecoSystems);
    }
}
