<?php

namespace App\Services;

use App\Enums\EcoMembershipRole;
use App\Enums\InvoiceStatus;
use App\Enums\OperationMassType;
use App\Models\EcoMembership;
use App\Models\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{

    public function __construct()
    {}

    public function outgoingMasses(string $getherers = null, string $ecoDuties = null, array $params = [])
    {
        return [];
    }

    public function collidencesByOrganization(string $getherer = null, string $ecoDuties = null, array $params = []): array
    {
        return [];
    }

    public function invoicesByStatus(string $getherer = null, string $ecoDuties = null, array $params = []): array
    {
        $statuses = [
            'rejeitadas' => [
                'total' => 0,
                'related_status' => [
                    InvoiceStatus::INVALID,
                    InvoiceStatus::REJECTED,
                ],
            ],
            'validas' => [
                'total' => 0,
                'related_status' => [
                    InvoiceStatus::VALID
                ],
            ],
        ];

        foreach ($statuses as $key => $status) {
            $statuses[$key]['total'] = Invoice::whereIn('status', $status['related_status'])->count();
        }

        return $statuses;
    }

    public function operationMassesByOperators(string $ecoDuties = null): array
    {
        $operators = EcoMembership::with('memberOrganization')->operators()
            ->whereHas('operationMasses', function ($operationMassquery) {
                $operationMassquery->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT);
            })
            ->when(!is_null($ecoDuties), function ($opertorsByEcoDutiesQuery) use ($ecoDuties) {
                $opertorsByEcoDutiesQuery->whereIn('eco_duty_id', explode(',', $ecoDuties));
            })
            ->get();

        $operationMassesByMaterialTypes = DB::table('operation_masses')
            ->select([
                'material_types.id as material_type_id',
                'material_types.code',
                'material_types.name',
                'eco_membership_id',
                'eco_memberships.eco_duty_id as eco_duty_id',
                'organizations.id as organization_id',
                'organizations.front_name',
                DB::raw('SUM(mass_kg) as mass_kg'),
            ])
            ->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->join('eco_memberships', 'eco_memberships.id', '=', 'operation_masses.eco_membership_id')
            ->join('organizations', 'organizations.id', '=', 'eco_memberships.member_organization_id')
            ->whereIn('eco_membership_id', $operators->modelKeys())
            ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
            ->groupBy(
                'material_types.id',
                'material_types.code',
                'material_types.name',
                'eco_membership_id',
                'eco_memberships.eco_duty_id',
                'organizations.id',
                'organizations.front_name',
            )
            ->orderBy('material_types.id')
            ->whereNull('organizations.deleted_at')
            ->whereNull('eco_memberships.deleted_at')
            ->whereNull('material_types.deleted_at')
            ->whereNull('operation_masses.deleted_at')
            ->get();

        $result = [];

        foreach ($operators as $key => $operator) {
            $result[$key] = [
                'organization_id' => $operator->member_organization_id,
                'eco_membership_id' => $operator->id,
                'legal_name' => $operator->memberOrganization->legal_name,
                'front_name' => $operator->memberOrganization->front_name,
                'materials' => []
            ];

            foreach ($operationMassesByMaterialTypes as $operationMassesByMaterialType) {
                if ($result[$key]['eco_membership_id'] === $operationMassesByMaterialType->eco_membership_id) {
                    $result[$key]['materials'][] = [
                        'id' => $operationMassesByMaterialType->material_type_id,
                        'code' => $operationMassesByMaterialType->code,
                        'name' => $operationMassesByMaterialType->name,
                        'mass_kg' => $operationMassesByMaterialType->mass_kg,
                    ];
                }
            }
        }

        return $result;
    }

    public function operationMassesByMaterialTypes(string $ecoDuties = null): Collection
    {
        $operators = DB::table('eco_memberships')
            ->where('member_role', EcoMembershipRole::OPERATOR)
            ->when(!is_null($ecoDuties), function ($operatorByEcoDutiesQuery) use ($ecoDuties) {
                $operatorByEcoDutiesQuery->whereIn('eco_duty_id', explode(',', $ecoDuties));
            })
            ->whereNull('eco_memberships.deleted_at')
            ->get();

        $operatorsId =  [];

        foreach ($operators as $operator) {
            $operatorsId[] = $operator->id;
        }

        $operationMassesByMaterialTypes = DB::table('operation_masses')
            ->select([
                'material_types.code',
                'material_types.name',
                'operation_mass_type',
                DB::raw('SUM(mass_kg) as mass_kg'),
            ])
            ->join('material_types', 'material_types.id', '=', 'operation_masses.material_type_id')
            ->whereIn('eco_membership_id', $operatorsId)
            ->whereIn('operation_mass_type', [
                OperationMassType::VALIDATED_INCOMING_WEIGHT,
                OperationMassType::VALIDATED_OUTGOING_WEIGHT
            ])
            ->groupBy(
                'material_types.code',
                'material_types.name',
                'operation_mass_type'
            )
            ->whereNull('operation_masses.deleted_at')
            ->get();

        return $operationMassesByMaterialTypes;
    }

    public function ecoMembershipsByRoles(string $ecoDuties = null): Collection
    {
        $quantityEcoMembershipsByRole = DB::table('eco_memberships')
            ->select([
                'member_role',
                DB::raw('COUNT(*) as quantity')
            ])
            ->groupBy('member_role')
            ->when(!is_null($ecoDuties), function ($ecoMembershipsByEcoDutiesQuery) use ($ecoDuties) {
                $ecoMembershipsByEcoDutiesQuery->whereIn('eco_duty_id', explode(',', $ecoDuties));
            })
            ->whereNull('eco_memberships.deleted_at')
            ->get();

        return $quantityEcoMembershipsByRole;
    }
}
