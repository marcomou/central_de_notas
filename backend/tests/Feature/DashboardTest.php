<?php

namespace Tests\Feature;

use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\EcoSystem;
use App\Models\MaterialType;
use App\Models\OperationMass;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->generateOperationMasses();
    }

    public function test_some_organization_is_allowed()
    {
        $someOrganization = Organization::factory()->create();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $someOrganization));

        $response->assertForbidden();

        $this->assertFalse($someOrganization->isFederalOrganization());
        $this->assertFalse($someOrganization->isSupervisingOrganization());
        $this->assertFalse($someOrganization->isManagingOrganization());
    }

    public function test_managing_organization_is_allowed()
    {
        $someOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $someOrganization));

        $response->assertOk();

        $this->assertFalse($someOrganization->isFederalOrganization());
        $this->assertFalse($someOrganization->isSupervisingOrganization());
        $this->assertTrue($someOrganization->isManagingOrganization());
    }

    public function test_supervising_organization_is_allowed()
    {
        $supervisingOrganization = $this->getSupervigingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $supervisingOrganization));

        $response->assertOk();

        $this->assertFalse($supervisingOrganization->isFederalOrganization());
        $this->assertTrue($supervisingOrganization->isSupervisingOrganization());
        $this->assertFalse($supervisingOrganization->isManagingOrganization());
    }

    public function test_federal_organization_is_allowed()
    {
        $federalOrganization = $this->getFederalOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $federalOrganization));

        $response->assertOk();

        $this->assertTrue($federalOrganization->isFederalOrganization());
        $this->assertFalse($federalOrganization->isSupervisingOrganization());
        $this->assertFalse($federalOrganization->isManagingOrganization());
    }

    public function test_managing_organization_dashboard_operation_masses_by_material_types()
    {
        $managingOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $managingOrganization));

        $response->assertOk();

        $ecoMemberships = EcoMembership::operators()->whereIn('eco_duty_id', $managingOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($response->json('data') as $data) {
            $sumOperationMasses = $this->getSumOperationMasses(
                materialTypeCode: $data['code'],
                operationMassType: $data['operation_mass_type'],
                ecoMemberships: $ecoMemberships->pluck('id')
            );

            $this->assertEquals($data['mass_kg'], $sumOperationMasses);
        }
    }

    public function test_supervising_organization_dashboard_operation_masses_by_material_types()
    {
        $supervisingOrganization = $this->getSupervigingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $supervisingOrganization));

        $response->assertOk();

        $ecoMemberships = EcoMembership::operators()->whereIn('eco_duty_id', $supervisingOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($response->json('data') as $data) {
            $sumOperationMasses = $this->getSumOperationMasses(
                materialTypeCode: $data['code'],
                operationMassType: $data['operation_mass_type'],
                ecoMemberships: $ecoMemberships->pluck('id')
            );

            $this->assertEquals($data['mass_kg'], $sumOperationMasses);
        }
    }

    public function test_federal_organization_dashboard_operation_masses_by_material_types()
    {
        $federalOrganization = $this->getSupervigingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', $federalOrganization));

        $response->assertOk();

        $ecoMemberships = EcoMembership::operators()->whereIn('eco_duty_id', $federalOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($response->json('data') as $data) {
            $sumOperationMasses = $this->getSumOperationMasses(
                materialTypeCode: $data['code'],
                operationMassType: $data['operation_mass_type'],
                ecoMemberships: $ecoMemberships->pluck('id')
            );

            $this->assertEquals($data['mass_kg'], $sumOperationMasses);
        }
    }

    public function test_managing_organization_dashboard_operation_masses_by_operators()
    {
        $managingOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_operators', $managingOrganization));

        $response->assertOk();

        $operators = EcoMembership::operators()->whereIn('eco_duty_id', $managingOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($operators as $operator) {
            foreach ($response->json('data') as $data) {
                foreach ($data['materials'] as $materialType) {

                    if ($data['eco_membership_id'] === $operator->id) {
                        $sumOperationMassByMaterialType =  $this->getSumOperationMasses(
                            materialTypeCode: $materialType['code'],
                            operationMassType: OperationMassType::VALIDATED_OUTGOING_WEIGHT,
                            ecoMemberships: [$operator->id],
                        );

                        $this->assertEquals($materialType['mass_kg'], $sumOperationMassByMaterialType);
                    }
                }
            }
        }
    }

    public function test_supervising_organization_dashboard_operation_masses_by_operators()
    {
        $supervisingOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_operators', $supervisingOrganization));

        $response->assertOk();

        $operators = EcoMembership::operators()->whereIn('eco_duty_id', $supervisingOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($operators as $operator) {
            foreach ($response->json('data') as $data) {
                foreach ($data['materials'] as $materialType) {

                    if ($data['eco_membership_id'] === $operator->id) {
                        $sumOperationMassByMaterialType =  $this->getSumOperationMasses(
                            materialTypeCode: $materialType['code'],
                            operationMassType: OperationMassType::VALIDATED_OUTGOING_WEIGHT,
                            ecoMemberships: [$operator->id],
                        );

                        $this->assertEquals($materialType['mass_kg'], $sumOperationMassByMaterialType);
                    }
                }
            }
        }
    }

    public function test_federal_organization_dashboard_operation_masses_by_operators()
    {
        $federalOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_operators', $federalOrganization));

        $response->assertOk();

        $operators = EcoMembership::operators()->whereIn('eco_duty_id', $federalOrganization->getEcoDuties()->pluck('id'))->get();

        foreach ($operators as $operator) {
            foreach ($response->json('data') as $data) {
                foreach ($data['materials'] as $materialType) {

                    if ($data['eco_membership_id'] === $operator->id) {
                        $sumOperationMassByMaterialType =  $this->getSumOperationMasses(
                            materialTypeCode: $materialType['code'],
                            operationMassType: OperationMassType::VALIDATED_OUTGOING_WEIGHT,
                            ecoMemberships: [$operator->id],
                        );

                        $this->assertEquals($materialType['mass_kg'], $sumOperationMassByMaterialType);
                    }
                }
            }
        }
    }

    public function test_managing_organization_dashboard_quantity_eco_memberships_by_roles()
    {
        $managingOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.eco_memberships_by_role', $managingOrganization));

        $response->assertOk();

        $ecoDuties = $managingOrganization->getEcoDuties()->pluck('id');

        foreach ($response->json('data') as $data) {
            $sumEcoMembershipsByRole = $this->getCountEcoMemberships($data['member_role'], $ecoDuties);

            $this->assertEquals($data['quantity'], $sumEcoMembershipsByRole);
        }
    }

    public function test_supervising_organization_dashboard_quantity_eco_memberships_by_roles()
    {
        $supervisingOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.eco_memberships_by_role', $supervisingOrganization));

        $response->assertOk();

        $ecoDuties = $supervisingOrganization->getEcoDuties()->pluck('id');

        foreach ($response->json('data') as $data) {
            $sumEcoMembershipsByRole = $this->getCountEcoMemberships($data['member_role'], $ecoDuties);

            $this->assertEquals($data['quantity'], $sumEcoMembershipsByRole);
        }
    }

    public function test_federal_organization_dashboard_quantity_eco_memberships_by_roles()
    {
        $federalOrganization = $this->getManagingOrganization();

        $response = $this->json('GET', route('organizations.dashboard.eco_memberships_by_role', $federalOrganization));

        $response->assertOk();

        $ecoDuties = $federalOrganization->getEcoDuties()->pluck('id');

        foreach ($response->json('data') as $data) {
            $sumEcoMembershipsByRole = $this->getCountEcoMemberships($data['member_role'], $ecoDuties);

            $this->assertEquals($data['quantity'], $sumEcoMembershipsByRole);
        }
    }

    // public function test_dashboard_operation_masses_by_material_types_filtered_by_eco_duty()
    // {
    //     $ecoDuty = DB::table('eco_duties')->inRandomOrder()->first();

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', [
    //         'ecoDuties' => $ecoDuty->id,
    //         'organization' => $organization
    //     ]));

    //     $response->assertOk();

    //     $operators = EcoMembership::operators()
    //         ->where('eco_duty_id', $ecoDuty->id)
    //         ->get('id');

    //     $operatorsId = [];

    //     foreach ($operators as $operator) {
    //         $operatorsId[] = $operator->id;
    //     }

    //     foreach ($response->json('data') as $data) {
    //         $materialType = DB::table('material_types')->where('code', $data['code'])->first();

    //         $sumOperationMassByMaterialType = OperationMass::query()
    //             ->where('material_type_id', $materialType->id)
    //             ->where('operation_mass_type', $data['operation_mass_type'])
    //             ->whereIn('eco_membership_id', $operatorsId)
    //             ->sum('mass_kg');

    //         $this->assertEquals($data['mass_kg'], $sumOperationMassByMaterialType);
    //     }
    // }

    // public function test_dashboard_operation_masses_by_material_types_filtered_by_eco_duties()
    // {
    //     $ecoDuties = DB::table('eco_duties')->inRandomOrder()->take(2)->get();
    //     $ecoDutiesId = [];

    //     foreach ($ecoDuties as $ecoDuty) {
    //         $ecoDutiesId[] = $ecoDuty->id;
    //     }

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_material_types', [
    //         'ecoDuties' => implode(',', $ecoDutiesId),
    //         'organization' => $organization
    //     ]));

    //     $response->assertOk();

    //     $operators = EcoMembership::operators()
    //         ->whereIn('eco_duty_id', $ecoDutiesId)
    //         ->get('id');

    //     $operatorsId = [];

    //     foreach ($operators as $operator) {
    //         $operatorsId[] = $operator->id;
    //     }

    //     foreach ($response->json('data') as $data) {
    //         $materialType = DB::table('material_types')->where('code', $data['code'])->first();

    //         $sumOperationMassByMaterialType = OperationMass::query()
    //             ->where('material_type_id', $materialType->id)
    //             ->where('operation_mass_type', $data['operation_mass_type'])
    //             ->whereIn('eco_membership_id', $operatorsId)
    //             ->sum('mass_kg');

    //         $this->assertEquals($data['mass_kg'], $sumOperationMassByMaterialType);
    //     }
    // }



    // public function test_dashboard_operation_masses_by_operators_filtered_by_eco_duty()
    // {
    //     $ecoDuty = DB::table('eco_duties')->inRandomOrder()->first();

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_operators', $organization, [
    //         'ecoDuties' => $ecoDuty->id,
    //     ]));

    //     $response->assertOk();

    //     $operators = EcoMembership::operators()
    //         ->where('eco_duty_id', $ecoDuty->id)
    //         ->get('id');

    //     foreach ($operators as $operator) {
    //         foreach ($response->json('data') as $data) {
    //             foreach ($data['materials'] as $materialType) {

    //                 if ($data['eco_membership_id'] === $operator->id) {
    //                     $sumOperationMassByMaterialType = OperationMass::query()
    //                         ->where('material_type_id', $materialType['id'])
    //                         ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
    //                         ->where('eco_membership_id', $operator->id)
    //                         ->sum('mass_kg');

    //                     $this->assertEquals($materialType['mass_kg'], $sumOperationMassByMaterialType);
    //                 }
    //             }
    //         }
    //     }
    // }

    // public function test_dashboard_operation_masses_by_operators_filtered_by_eco_duties()
    // {
    //     $ecoDuties = DB::table('eco_duties')->inRandomOrder()->take(2)->get();
    //     $ecoDutiesId = [];

    //     foreach ($ecoDuties as $ecoDuty) {
    //         $ecoDutiesId[] = $ecoDuty->id;
    //     }

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.operation_masses_by_operators', $organization, [
    //         'ecoDuties' => implode(',', $ecoDutiesId),
    //     ]));

    //     $response->assertOk();

    //     $operators = EcoMembership::operators()
    //         ->whereIn('eco_duty_id', $ecoDutiesId)
    //         ->get('id');

    //     foreach ($operators as $operator) {
    //         foreach ($response->json('data') as $data) {
    //             foreach ($data['materials'] as $materialType) {

    //                 if ($data['eco_membership_id'] === $operator->id) {
    //                     $sumOperationMassByMaterialType = OperationMass::query()
    //                         ->where('material_type_id', $materialType['id'])
    //                         ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
    //                         ->where('eco_membership_id', $operator->id)
    //                         ->sum('mass_kg');

    //                     $this->assertEquals($materialType['mass_kg'], $sumOperationMassByMaterialType);
    //                 }
    //             }
    //         }
    //     }
    // }



    // public function test_quantity_eco_memberships_by_roles_filtered_by_eco_duty()
    // {
    //     EcoMembership::factory(10)->create();

    //     $ecoDuty = DB::table('eco_duties')->inRandomOrder()->first();

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.eco_memberships_by_role', $organization), [
    //         'ecoDuties' => $ecoDuty->id,
    //     ]);

    //     foreach ($response->json('data') as $data) {
    //         $sumEcoMembershipsByRole = EcoMembership::query()
    //             ->where('eco_duty_id', $ecoDuty->id)
    //             ->where('member_role', $data['member_role'])
    //             ->count();

    //         $this->assertEquals($data['quantity'], $sumEcoMembershipsByRole);
    //     }
    // }

    // public function test_quantity_eco_memberships_by_roles_filtered_by_eco_duties()
    // {
    //     EcoMembership::factory(10)->create();

    //     $ecoDuties = DB::table('eco_duties')->inRandomOrder()->take(2)->get();
    //     $ecoDutiesId = [];

    //     foreach ($ecoDuties as $ecoDuty) {
    //         $ecoDutiesId[] = $ecoDuty->id;
    //     }

    //     $organization = Organization::factory()->create();
    //     $response = $this->json('GET', route('organizations.dashboard.eco_memberships_by_role', $organization), [
    //         'ecoDuties' => implode(',', $ecoDutiesId),
    //     ]);

    //     foreach ($response->json('data') as $data) {
    //         $sumEcoMembershipsByRole = EcoMembership::query()
    //             ->whereIn('eco_duty_id', $ecoDutiesId)
    //             ->where('member_role', $data['member_role'])
    //             ->count();

    //         $this->assertEquals($data['quantity'], $sumEcoMembershipsByRole);
    //     }
    // }

    private function getManagingOrganization(): Organization
    {
        return EcoDuty::with('managingOrganization')->inRandomOrder()->first()->managingOrganization;
    }

    private function getSupervigingOrganization(): Organization
    {
        return EcoSystem::with('supervisingOrganization')->inRandomOrder()->first()->supervisingOrganization;
    }

    private function getFederalOrganization(): Organization
    {
        return Organization::factory()->federal()->create();
    }

    private function makeSupervisingOrganization(): Organization
    {
        $supervisingOrganization = Organization::factory()->create();

        EcoSystem::factory()->create([
            'supervising_organization_id' => $supervisingOrganization->id,
        ]);

        return $supervisingOrganization;
    }

    private function generateOperationMasses()
    {
        $materialTypes = MaterialType::factory(rand(1, 3))->create();

        $ecoDuties = EcoDuty::factory(rand(2, 4))->create();

        foreach ($ecoDuties as $ecoDuty) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                $organization = Organization::factory()->create();

                $operator = EcoMembership::create([
                    'eco_duty_id' => $ecoDuty->id,
                    'member_organization_id' => $organization->id,
                    'member_role' => EcoMembershipRole::OPERATOR
                ]);

                $materialTypes->each(function ($materialType) use ($operator) {
                    for ($i = 0; $i < rand(5, 10); $i++) {
                        OperationMass::create([
                            'eco_membership_id' => $operator->id,
                            'mass_kg' => rand(100, 1000),
                            'material_type_id' => $materialType->id,
                            'work_year' => rand(2019, 2022),
                            'operation_mass_type' => array_random([
                                OperationMassType::VALIDATED_INCOMING_WEIGHT,
                                OperationMassType::VALIDATED_OUTGOING_WEIGHT,
                            ]),
                        ]);
                    }
                });

                $organization = Organization::factory()->create();

                EcoMembership::create([
                    'eco_duty_id' => $ecoDuty->id,
                    'member_organization_id' => $organization->id,
                    'member_role' => array_random(EcoMembershipRole::asArray())
                ]);
            }
        }
    }

    private function getSumOperationMasses(string $materialTypeCode = null, string $operationMassType = null, $ecoMemberships = [])
    {
        return OperationMass::query()
            ->when($materialTypeCode, fn ($query) => $query->whereHas('materialType', fn ($query) => $query->where('code', $materialTypeCode)))
            ->when($operationMassType, fn ($query) => $query->where('operation_mass_type', $operationMassType))
            ->when(count($ecoMemberships), fn ($query) => $query->whereIn('eco_membership_id', $ecoMemberships))
            ->groupBy('material_type_id')
            ->groupBy('operation_mass_type')
            ->sum('mass_kg');
    }

    private function getCountEcoMemberships(string $memberRole = null, $ecoDuties = [])
    {
        return EcoMembership::query()
            ->when($memberRole, fn ($query) => $query->where('member_role', $memberRole))
            ->when(count($ecoDuties), fn ($query) => $query->whereIn('eco_duty_id', $ecoDuties))
            ->groupBy('eco_membership_role')
            ->count();
    }
}
