<?php

namespace App\Http\Controllers;

use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use App\Http\Requests\EcoDuty\StoreRequest;
use App\Http\Requests\EcoDuty\UpdateRequest;
use App\Http\Resources\EcoDutyResource;
use App\Http\Resources\EcoDutyReviewResource;
use App\Http\Resources\EcoMembershipResource;
use App\Http\Resources\LiabilityDeclarationResource;
use App\Http\Resources\Report\EcoDutyReportOperationMassByOperator;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\Invoice;
use App\Models\MaterialType;
use App\Models\EcoDutyReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcoDutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $year = $request->get('year');
        $state = $request->get('state');

        $ecoDuties = EcoDuty::with(['ecoRuleset', 'managingOrganization'])
            ->when($year, function ($query) use ($year) {
                $query->whereHas('ecoRuleset', function ($query) use ($year) {
                    $query->where("duty_year", "$year");
                });
            })
            ->when($q, function ($query) use ($q) {
                $q = preg_replace('/\s+/', '%', trim($q));
                $query->whereHas('managingOrganization', function ($query) use ($q) {
                    $query->where("federal_registration", "like", "%$q%")
                        ->orWhere("legal_name", "like", "%$q%");
                })->orWhere('managing_code', 'like', "%$q%");
            })
            ->when($state, function ($query) use ($state) {
                $query->whereHas('ecoRuleset.ecoSystem.location', function ($query) use ($state) {
                    $query->where("acronym", "$state");
                });
            })->paginate();

        return EcoDutyResource::collection($ecoDuties);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EcoDuty\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $ecoDuty = EcoDuty::create($request->validated());

        return new EcoDutyResource($ecoDuty);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcoDuty  $ecoDuty
     * @return \Illuminate\Http\Response
     */
    public function show(EcoDuty $ecoDuty)
    {
        $ecoDuty->load([
            'ecoRuleset',
            'managingOrganization'
        ]);

        return new EcoDutyResource($ecoDuty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EcoDuty\UpdateRequest  $request
     * @param  \App\Models\EcoDuty  $ecoDuty
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, EcoDuty $ecoDuty)
    {
        $ecoDuty->update($request->validated());

        return new EcoDutyResource($ecoDuty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcoDuty  $ecoDuty
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoDuty $ecoDuty)
    {
        $ecoDuty->delete();

        return response()->noContent();
    }

    public function ecoMemberships(Request $request, EcoDuty $ecoDuty)
    {
        // $ecoDuty->load([
        //     'ecoMemberships' => function ($ecoMembershipQuery) use ($request) {
        //         $ecoMembershipQuery->when($request->get('member_role'), function ($ecoMembershipQuery) use ($request) {
        //             $ecoMembershipQuery->byMemberRole($request->member_role);
        //         })->when($request->get('search'), function ($ecoMembershipQuery) use ($request) {
        //             $ecoMembershipQuery->whereHas('memberOrganization', function ($memberOrganizationQuery) use ($request) {
        //                 $memberOrganizationQuery->where('federal_registration', 'like', "%{$request->search}%")
        //                     ->orWhere('legal_name', 'like', "%{$request->search}%")
        //                     ->orWhere('front_name', 'like', "%{$request->search}%");
        //             });
        //         });
        //     }
        // ]);

        // return EcoMembershipResource::collection($ecoDuty->ecoMemberships);

        $ecoMembershipQuery = EcoMembership::query();

        if (
            $request->member_role === EcoMembershipRole::OPERATOR
            || $request->member_role === EcoMembershipRole::RECYCLER
        ) {
            $organizationIds = [];

            $ecoDuty->managingOrganization->invoiceFiles()
                ->with(['invoice' => function ($query) {
                    $query->select('id', 'issuer_id', 'recipient_id');
                }])
                ->select('invoice_id')
                ->get()
                ->each(function($invoiceFile) use (&$organizationIds) {
                    if ($invoiceFile->invoice) {
                        $organizationIds[] = $invoiceFile->invoice->issuer_id;
                        $organizationIds[] = $invoiceFile->invoice->recipient_id;
                    }
                });

            $organizationIds = array_unique($organizationIds);

            $ecoMembershipQuery->whereIn('member_organization_id', $organizationIds);
        }

        $ecoMembershipQuery->when($request->get('member_role'), function ($ecoMembershipQuery) use ($request) {
            $ecoMembershipQuery->byMemberRole($request->member_role);
        })->when($request->get('search'), function ($ecoMembershipQuery) use ($request) {
            $ecoMembershipQuery->whereHas('memberOrganization', function ($memberOrganizationQuery) use ($request) {
                $memberOrganizationQuery->where('federal_registration', 'like', "%{$request->search}%")
                    ->orWhere('legal_name', 'like', "%{$request->search}%")
                    ->orWhere('front_name', 'like', "%{$request->search}%");
            });
        });

        return EcoMembershipResource::collection($ecoMembershipQuery->paginate());
    }

    public function liabilityDeclarations(EcoDuty $ecoDuty)
    {
        $ecoDuty->load([
            'liabilityDeclarations',
            'operators',
        ]);

        $operators = $ecoDuty->operators;
        $libilityDeclarations = $ecoDuty->liabilityDeclarations;
        $operationMasses = DB::table('operation_masses')
            ->select(
                'material_types.id',
                'material_types.name',
                'material_types.code',
                'material_types.name',
                DB::raw("(CASE WHEN operation_mass_type = '" . OperationMassType::VALIDATED_OUTGOING_WEIGHT . "' THEN SUM(mass_kg) ELSE 0 END) AS mass_kg"),
            )->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
            ->whereIn('eco_membership_id', $operators->modelKeys())
            ->groupBy('operation_mass_type', 'material_type_id')
            ->get();

        $result = [];

        foreach ($libilityDeclarations as $libilityDeclaration) {

            $materialTypeCode = $libilityDeclaration->materialType->code;

            if (array_key_exists($libilityDeclaration->materialType->code, $ecoDuty->material_types)) {
                $percentMaterialType = $ecoDuty->material_types[$materialTypeCode];

                $operationMass = $operationMasses->firstWhere('code', $materialTypeCode);

                $massWeightValidated =  $operationMass ? $operationMass->mass_kg : 0;
                $massWeightDefined = $libilityDeclaration->mass_kg * $percentMaterialType / 100;
                $result[] = [
                    'result' => [
                        'percentage' => $percentMaterialType,
                        'mass_weight_defined' => $massWeightDefined,
                        'mass_weight_validated' => $massWeightValidated,
                    ],
                ] + $libilityDeclaration->toArray();
            }
        }


        return response()->json([
            'data' => $result
        ]);
        LiabilityDeclarationResource::collection($ecoDuty->liabilityDeclarations);
    }

    public function reviews(Request $request, EcoDuty $ecoDuty)
    {
        $ecoDutyReviews = EcoDutyReview::query()
            ->when($request->get('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->where('eco_duty_id', $ecoDuty->id)
            ->latest('sequence_number')
            ->paginate($request->perPage);

        return EcoDutyReviewResource::collection($ecoDutyReviews);
    }

    public function quantityByOperator(EcoDuty $ecoDuty)
    {
        $incoming = OperationMassType::VALIDATED_INCOMING_WEIGHT;
        $outgoing = OperationMassType::VALIDATED_OUTGOING_WEIGHT;

        $ecoDuty->load(['operators' => function ($operatorQuery) use ($incoming, $outgoing) {
            $operatorQuery->with(['operationMasses' => function ($operationMassQuery) use ($incoming, $outgoing) {
                $operationMassQuery->select(
                    'eco_membership_id',
                    'operation_mass_type',
                    DB::raw("(CASE WHEN operation_mass_type = '{$incoming}' THEN SUM(mass_kg) ELSE 0 END) AS {$incoming}"),
                    DB::raw("(CASE WHEN operation_mass_type = '{$outgoing}' THEN SUM(mass_kg) ELSE 0 END) AS {$outgoing}"),
                )
                    ->whereIn('operation_mass_type', [$incoming, $outgoing])
                    ->groupBy('eco_membership_id', 'operation_mass_type');
            }]);
        }]);

        return EcoDutyReportOperationMassByOperator::collection($ecoDuty->operators);
    }

    public function quantityByMaterialTypes(EcoDuty $ecoDuty)
    {
        $definedGoalsPercentByMaterialType = $ecoDuty->metadata['operational_data']['recycling_credit_system_residual_percent'];

        $operatorsOfEcoDuty = $ecoDuty->operators;

        $materialTypesOfEcoduty = MaterialType::whereIn('code', array_keys($definedGoalsPercentByMaterialType))->get();

        $libilityDeclarations = DB::table("liability_declarations")
            ->select(
                'material_types.id',
                'material_types.name',
                'material_types.code',
                'material_types.name',
                DB::raw("SUM(mass_kg) as mass_kg"),
            )
            ->join('material_types', 'material_types.id', 'liability_declarations.material_type_id')
            ->where('eco_duty_id', $ecoDuty->id)
            ->whereIn('material_type_id', $materialTypesOfEcoduty->modelKeys())
            ->groupBy('material_type_id')
            ->get();

        $operationMasses = DB::table('operation_masses')
            ->select(
                'material_types.id',
                'material_types.name',
                'material_types.code',
                'material_types.name',
                DB::raw("(CASE WHEN operation_mass_type = '" . OperationMassType::VALIDATED_OUTGOING_WEIGHT . "' THEN SUM(mass_kg) ELSE 0 END) AS mass_kg"),
            )->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
            ->whereIn('eco_membership_id', $operatorsOfEcoDuty->modelKeys())
            ->whereIn('material_type_id', $materialTypesOfEcoduty->modelKeys())
            ->groupBy('operation_mass_type', 'material_type_id')
            ->get();

        $result = [];

        foreach ($libilityDeclarations as $libilityDeclaration) {

            foreach ($operationMasses as $operationMass) {

                if ($libilityDeclaration->code === $operationMass->code) {

                    $percentage = $definedGoalsPercentByMaterialType[$libilityDeclaration->code];

                    $massaAserComprovada = $libilityDeclaration->mass_kg * $percentage / 100;

                    $result[] = [
                        'id' => $libilityDeclaration->id,
                        'name' => $libilityDeclaration->name,
                        'code' => $libilityDeclaration->code,
                        'mass_kg' => $massaAserComprovada,
                        'done' => $operationMass->mass_kg > $massaAserComprovada  ? true : false,
                    ];
                }
            }
        }

        return response()->json([
            'data' => [
                'liability_declarations' => $libilityDeclarations,
                'defined_goals_percent' => $definedGoalsPercentByMaterialType,
                'defined_goals_weight_mass' => $result,
                'validated_outgoing_operation_masses' => $operationMasses,
            ]
        ]);
    }
}
