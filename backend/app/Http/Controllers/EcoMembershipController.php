<?php

namespace App\Http\Controllers;

use App\Enums\OperationMassType;
use App\Http\Requests\EcoMembershipRequest;
use App\Http\Resources\ContactResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\EcoMembershipResource;
use App\Http\Resources\HomologationDiagnosticResource;
use App\Http\Resources\LiabilityDeclarationResource;
use App\Http\Resources\OperationMassResource;
use App\Models\EcoMembership;
use App\Models\InvoiceFile;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcoMembershipController extends Controller
{
    function __construct()
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ecoMemberships = EcoMembership::when($request->get('member_role'), function ($query) use ($request) {
            $query->byMemberRole($request->member_role);
        })->with([
            'ecoDuty',
            'memberOrganization',
            'throughMembership.memberOrganization',
        ])->paginate()
            ->withQueryString();

        return EcoMembershipResource::collection($ecoMemberships);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EcoMembershipRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EcoMembershipRequest $request)
    {
        $attributes = collect($request->validated());
        $ecoMembership = EcoMembership::create(array_merge([
            'extra' => [
                'data' => $attributes->get('extra', [])
            ]
        ], $attributes->except('extra')->all()));

        return new EcoMembershipResource($ecoMembership);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcoMembership  $ecoMembership
     * @return \Illuminate\Http\Response
     */
    public function show(EcoMembership $ecoMembership)
    {
        $ecoMembership->load([
            'ecoDuty',
            'contacts',
            'memberOrganization',
            'throughMembership.memberOrganization',
        ]);

        return new EcoMembershipResource($ecoMembership);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EcoMembershipRequest  $request
     * @param  \App\Models\EcoMembership  $ecoMembership
     * @return \Illuminate\Http\Response
     */
    public function update(EcoMembershipRequest $request, EcoMembership $ecoMembership)
    {
        $attributes = collect($request->validated());

        $ecoMembership->update(array_merge([
            'extra' => [
                'data' => array_merge($ecoMembership->extra['data'] ?? [], $attributes->get('extra', []))
            ]
        ], $attributes->except('extra')->all()));

        return new EcoMembershipResource($ecoMembership);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcoMembership  $ecoMembership
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoMembership $ecoMembership)
    {
        $ecoMembership->delete();

        return response()->noContent();
    }

    public function contacts(Request $request, EcoMembership $ecoMembership)
    {
        $ecoMembership->load([
            'contacts' => function ($query) use ($request) {
                $query->when($request->get('role'), function ($contactQuery) use ($request) {
                    $contactQuery->where('role', $request->role);
                });
            },
        ]);

        return ContactResource::collection($ecoMembership->contacts);
    }

    public function documents(EcoMembership $ecoMembership)
    {
        $ecoMembership->load('documents');

        return DocumentResource::collection($ecoMembership->documents);
    }

    public function operationMasses(EcoMembership $ecoMembership)
    {
        $ecoMembership->load('operationMasses');

        return OperationMassResource::collection($ecoMembership->operationMasses);
    }

    public function homologationDiagnostics(EcoMembership $ecoMembership)
    {
        $ecoMembership->load('homologationDiagnostics');

        return HomologationDiagnosticResource::collection($ecoMembership->homologationDiagnostics);
    }

    public function liabilityDeclarations(EcoMembership $ecoMembership)
    {
        $ecoMembership->load('liabilityDeclarations');

        return LiabilityDeclarationResource::collection($ecoMembership->liabilityDeclarations);
    }

    public function invoices(Request $request, EcoMembership $ecoMembership)
    {
        $ecoMembership->load('ecoDuty.managingOrganization');

        $invoiceFileQuery = $this->getInvoiceFilesQuery();
        $invoiceFileQuery->where('sent_by_organization_id', $ecoMembership->memberOrganization->id);

        $total = $invoiceFileQuery->count();
        $invoiceFile = $this->prepareInvoiceFilesDataResponse($request, $invoiceFileQuery);

        return response()->json([
            'data' => $invoiceFile->all(),
            'top' => $request->get('top', 10),
            'skip' => (int) $request->get('skip', 0),
            'total' => $total,
        ]);
    }

    public function resumeOperationMasses(EcoMembership $ecoMembership)
    {
        $ecoDuty = $ecoMembership->ecoDuty;

        $materialTypesOfEcoduty = MaterialType::whereIn('code', array_keys($ecoDuty->metadata['operational_data']['recycling_credit_system_residual_percent']))
            ->get();

        $operationMassStatuses = [
            OperationMassType::READ_INCOMING_WEIGHT, //Entrada lida
            OperationMassType::VALIDATED_INCOMING_WEIGHT, // Entrada validada
            OperationMassType::READ_OUTGOING_WEIGHT, // Saida lida
            OperationMassType::VALIDATED_OUTGOING_WEIGHT, // Saída validada
        ];

        $operationMassesByMaterialTypes = DB::table('operation_masses')
            ->select([
                'material_types.id as material_type_id',
                'material_types.code',
                'material_types.name',
                DB::raw('SUM(mass_kg) as mass_kg'),
                'operation_mass_type'
            ])
            ->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->join('eco_memberships', 'eco_memberships.id', '=', 'operation_masses.eco_membership_id')
            ->join('organizations', 'organizations.id', '=', 'eco_memberships.member_organization_id')
            ->where('eco_membership_id', $ecoMembership->id)
            ->whereIn('material_type_id', $materialTypesOfEcoduty->modelKeys())
            ->whereIn('operation_mass_type', $operationMassStatuses)
            ->groupBy(
                'material_type_id',
                'operation_mass_type',
            )
            ->orderBy('material_types.name')
            ->get();

        $result = [];

        foreach ($materialTypesOfEcoduty as $key => $materialType) {
            $result[$key] = [
                'id' => $materialType->id,
                'code' => $materialType->code,
                'name' => $materialType->name,
            ];

            foreach ($operationMassStatuses as $operationMassStatus) {
                $result[$key][$operationMassStatus] = 0;

                foreach ($operationMassesByMaterialTypes as $operationMassByMaterialType) {
                    if (
                        $operationMassByMaterialType->material_type_id === $materialType->id &&
                        $operationMassStatus === $operationMassByMaterialType->operation_mass_type
                    ) $result[$key][$operationMassStatus] = $operationMassByMaterialType->mass_kg;
                }
            }
        }

        return response()->json(['data' => $result]);
    }

    private function getInvoiceFilesQuery()
    {
        $invoiceFileQuery = InvoiceFile::query();

        $invoiceFileQuery->with([
            'invoice',
            'invoice.issuer',
            'invoice.recipient',
            'invoice.invoiceItems',
            'invoice.invoiceItems.materialType',
            'invoice.invoiceItems.materialType.materialTypeParent',
        ]);

        $invoiceFileQuery->orderByDesc('created_at');

        return $invoiceFileQuery;
    }

    private function prepareInvoiceFilesDataResponse($request, $invoiceFileQuery) {
        if ($skip = $request->get('skip', 0)) {
            $invoiceFileQuery->offset($skip);
        }

        if ($top = $request->get('top', 10)) {
            $invoiceFileQuery->limit($top);
        }

        return $invoiceFileQuery->get()->map(function ($invoiceFile) {
            $aggregatedMaterialMass = [];

            if ($invoiceFile->invoice) {
                $invoiceFile->invoice->invoiceItems->each(function($invoiceItem) use (&$aggregatedMaterialMass) {
                    if ($materialType = $invoiceItem->materialType) {
                        if ($materialType->materialTypeParent) {
                            $materialType = $materialType->materialTypeParent;
                        }

                        if (!array_key_exists($materialType->name, $aggregatedMaterialMass)) {
                            $aggregatedMaterialMass[$materialType->name] = $invoiceItem->mass_kg;
                            return;
                        }

                        $aggregatedMaterialMass[$materialType->name] += $invoiceItem->mass_kg;
                    }
                });
            }

            return [
                'ecoduty' => null,
                'email_guid' => null,
                'file_location' => null,
                'file_type' => null,
                'filename' => $invoiceFile->file_name,
                'gatherer' => $invoiceFile->sentByOrganization,
                'gatherer_guid' => $invoiceFile->sentByOrganization->getherer_guid,
                'guid' => $invoiceFile->id,
                'handled_with' => null,
                'invoices' => [$invoiceFile->invoice],
                'invoice' => $invoiceFile->invoice,
                'private_uri' => $invoiceFile->file_path,
                'public_uri' => $invoiceFile->file_path,
                'reading_duration_ms' => null,
                'status' => $invoiceFile->status,
                'status_reason' => $invoiceFile->status_reason,
                'tries_counter' => null,
                'aggregated_material_mass' => count($aggregatedMaterialMass) ? $aggregatedMaterialMass : null,
                'collidence' => $invoiceFile->status === $invoiceFile->isCollidence(),
            ];
        });
    }
}
