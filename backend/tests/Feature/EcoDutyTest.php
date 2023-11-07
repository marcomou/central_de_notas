<?php

namespace Tests\Feature;

use App\Enums\EcoDutyStatus;
use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use App\Models\LiabilityDeclaration;
use App\Models\MaterialType;
use App\Models\OperationMass;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EcoDutyTest extends TestCase
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

        Artisan::call('db:seed', ['class' => 'LegalTypeSeeder']);

        Organization::factory()->create();
        EcoSystem::factory()->create();
        EcoRuleset::factory()->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_delete_eco_duty()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $response = $this->json('DELETE', route('eco_duties.destroy', $ecoDuty));

        $response->assertNoContent();

        $this->assertSoftDeleted('eco_duties', ['id' => $ecoDuty->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_show_eco_duty()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $response = $this->json('GET', route('eco_duties.show', $ecoDuty));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_duty_because_is_deleted()
    {
        $ecoDuty = EcoDuty::factory()->create();
        $ecoDuty->delete();

        $this->assertSoftDeleted('eco_duties', ['id' => $ecoDuty->id]);

        $response = $this->json('DELETE', route('eco_duties.show', $ecoDuty));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_eco_duty_because_is_deleted()
    {
        $ecoDuty = EcoDuty::factory()->create();
        $ecoDuty->delete();

        $this->assertSoftDeleted('eco_duties', ['id' => $ecoDuty->id]);

        $response = $this->json('DELETE', route('eco_duties.destroy', $ecoDuty));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_duty_without_metadata()
    {
        $data = EcoDuty::factory()->make()->toArray();

        unset($data['metadata']);

        $response = $this->json(
            'POST',
            route('eco_duties.store'),
            $data
        );

        $managingOrganization = Organization::find($data['managing_organization_id']);

        $this->assertTrue($managingOrganization->isManagingOrganization());

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_duty_with_metadata()
    {
        $data = EcoDuty::factory()->make()->toArray();

        $response = $this->json('POST', route('eco_duties.store'), $data);

        $managingOrganization = Organization::find($data['managing_organization_id']);

        $this->assertTrue($managingOrganization->isManagingOrganization());

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_eco_duty()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $metaData = EcoDuty::factory()->make()->toArray()['metadata'];

        $response = $this->json(
            'PUT',
            route('eco_duties.update', $ecoDuty),
            [
                'metadata' => $metaData,
            ]
        );

        $response->assertOk();

        $this->assertEquals($metaData, $response->json('data.metadata'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_eco_duty_update_status_without_effect()
    {
        $statusDraft = EcoDutyStatus::fromValue(EcoDutyStatus::DRAFT);

        $ecoDuty = EcoDuty::factory()->create([
            'status' => $statusDraft->value
        ]);

        $response = $this->json(
            'PUT',
            route('eco_duties.update', $ecoDuty),
            ['status' => EcoDutyStatus::EDITING]
        );

        $response->assertOk();

        $this->assertFalse($statusDraft->is(EcoDutyStatus::fromValue($response->json('data.status'))->value));
        $this->assertDatabaseHas('eco_duties', ['id' => $ecoDuty->id, 'status' => $statusDraft->value]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_eco_duty_update_eco_ruleset_id_without_effect()
    {
        $otherEcoRuleset = EcoRuleset::factory()->create();
        $ecoDuty = EcoDuty::factory()->create();

        $response = $this->json(
            'PUT',
            route('eco_duties.update', $ecoDuty),
            ['eco_ruleset_id' => $otherEcoRuleset->id]
        );

        $response->assertOk();

        $this->assertDatabaseHas('eco_duties', ['id' => $ecoDuty->id, 'eco_ruleset_id' => $ecoDuty->eco_ruleset_id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_eco_duty_update_managing_organization_id_without_effect()
    {
        $otherOrganization = Organization::factory()->create();
        $ecoDuty = EcoDuty::factory()->create();

        $response = $this->json(
            'PUT',
            route('eco_duties.update', $ecoDuty),
            ['eco_ruleset_id' => $otherOrganization->id]
        );

        $response->assertOk();

        $this->assertDatabaseHas('eco_duties', ['id' => $ecoDuty->id, 'managing_organization_id' => $ecoDuty->managing_organization_id]);
    }

    public function test_successfully_result_by_operators_with_empty_operation_masses()
    {
        $ecoDuty = EcoDuty::factory()->create();

        EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
            'eco_duty_id' => $ecoDuty->id,
        ]);

        EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
            'eco_duty_id' => $ecoDuty->id,
        ]);

        $response = $this->json('GET', route('eco_duties.quantity_by_operator', $ecoDuty));

        $operators = $response->json('data');

        foreach ($operators as $key => $value) {
            $this->assertCount(0, $value['operation_masses']);
        }
    }

    public function test_successfully_result_by_operators_with_many_operation_masses_and_without_operation_masses()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $operatorOne = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
            'eco_duty_id' => $ecoDuty->id,
        ]);

        EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
            'eco_duty_id' => $ecoDuty->id,
        ]);

        $data = [
            [
                'operator' => $operatorOne->id,
                'data' => [
                    OperationMassType::VALIDATED_INCOMING_WEIGHT => [
                        'quantity' => rand(1, 4),
                        'mass_kg' => rand(10, 100),
                    ],
                    OperationMassType::VALIDATED_OUTGOING_WEIGHT => [
                        'quantity' => rand(1, 4),
                        'mass_kg' => rand(10, 100),
                    ]
                ]
            ],

            [
                'operator' => $operatorOne->id,
                'data' => [

                    OperationMassType::VALIDATED_INCOMING_WEIGHT => [
                        'quantity' => rand(1, 4),
                        'mass_kg' => rand(10, 100),
                    ],

                    OperationMassType::VALIDATED_OUTGOING_WEIGHT => [
                        'quantity' => rand(1, 4),
                        'mass_kg' => rand(10, 100),
                    ]
                ]
            ],
        ];

        foreach ($data as $item) {
            foreach ($item['data'] as $key => $value) {
                OperationMass::factory($value['quantity'])
                    ->create([
                        'eco_membership_id' => $item['operator'],
                        'operation_mass_type' => $key,
                        'mass_kg' => $value['mass_kg']
                    ]);
            }
        }

        $response = $this->json('GET', route('eco_duties.quantity_by_operator', $ecoDuty));

        $operators = $response->json('data');

        foreach ($operators as $operator) {
            foreach ($operator['operation_masses'] as $operatorMass) {
                $operationMassType = $operatorMass['operation_mass_type'];
                $validatedMassTypeTotal = $operatorMass[$operationMassType];

                $operationMassKg = 0;

                foreach ($data as $dataItem) {
                    if ($dataItem['operator'] === $operator['id']) {
                        $operationMassKg += $dataItem['data'][$operationMassType]['quantity'] * $dataItem['data'][$operationMassType]['mass_kg'];
                    }
                }

                $this->assertEquals($validatedMassTypeTotal, $operationMassKg);
            }
        }
    }

    public function test_report_by_material_types()
    {
        $materialTypes = MaterialType::factory(2)->create();

        $quantityLiabilityDeclaration = rand(3, 5);
        $massLiabilityDeclaration = rand(10, 100);

        $quantityOperationMass = rand(5, 10);
        $massOperationMass = rand(10, 100);
        $quantityOperators = rand(3, 5);

        $ecoDuty = EcoDuty::factory()->materialTypes([
            $materialTypes[0]->code => 30,
            $materialTypes[1]->code => 40,
        ])->create();

        $otherEcoDuty = EcoDuty::factory()->materialTypes([
            $materialTypes[0]->code => 20,
            $materialTypes[1]->code => 15,
        ])->create();

        for ($i = 0; $i < $quantityOperators; $i++) {

            $organization = Organization::factory()->create();

            $operator = EcoMembership::create([
                'eco_duty_id' => $ecoDuty->id,
                'member_role' => EcoMembershipRole::OPERATOR,
                'member_organization_id' => $organization->id,
            ]);

            $materialTypes->each(function ($materialType) use (
                $operator,
                $ecoDuty,
                $massLiabilityDeclaration,
                $quantityLiabilityDeclaration,
                $massOperationMass,
                $quantityOperationMass
            ) {
                for ($i = 0; $i < $quantityLiabilityDeclaration; $i++) {
                    LiabilityDeclaration::create([
                        'material_type_id' => $materialType->id,
                        'mass_kg' => $massLiabilityDeclaration,
                        'eco_duty_id' => $ecoDuty->id,
                        'eco_membership_id' => $operator->id,
                    ]);
                }

                for ($i = 0; $i < $quantityOperationMass; $i++) {
                    OperationMass::create([
                        'mass_kg' => $massOperationMass,
                        'material_type_id' => $materialType->id,
                        'eco_membership_id' => $operator->id,
                        'work_year' => array_random([2019, 2020]),
                        'operation_mass_type' => OperationMassType::VALIDATED_OUTGOING_WEIGHT,
                    ]);
                }
            });
        }

        $response = $this->json('GET', route('eco_duties.quantity_by_material_types', $ecoDuty));

        $response->assertOk();

        //Validar as liabilityDeclarations
        foreach ($response->json('data.liability_declarations') as $liabilityDeclaration) {
            $liabilityDeclarationByMaterialType = DB::table('liability_declarations')
                ->where('eco_duty_id', $ecoDuty->id)
                ->where('material_type_id', $liabilityDeclaration['id'])
                ->sum('mass_kg');

            $this->assertEquals($liabilityDeclaration['mass_kg'], $liabilityDeclarationByMaterialType);
        }

        //Validar as operationMass
        $operators = $ecoDuty->operators;
        foreach ($response->json('data.validated_outgoing_operation_masses') as $operationMass) {
            $operationMassByMaterialType = DB::table('operation_masses')
                ->whereIn('eco_membership_id', $operators->modelKeys())
                ->where('material_type_id', $operationMass['id'])
                ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
                ->sum('mass_kg');

            $this->assertEquals($operationMass['mass_kg'], $operationMassByMaterialType);
        }

        //Validar as porcentagens
        foreach ($response->json('data.defined_goals_percent') as $key => $value) {
            $percents = $ecoDuty->metadata['operational_data']['recycling_credit_system_residual_percent'];

            $this->assertEquals($value, $percents[$key]);
        }

        // Validar se atingiu a meta
        foreach ($response->json('data.defined_goals_weight_mass') as $definedGoalsWeightMass) {
            $operatedWeightMassSum = DB::table('operation_masses')
                ->whereIn('eco_membership_id', $operators->modelKeys())
                ->where('material_type_id', $definedGoalsWeightMass['id'])
                ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
                ->sum('mass_kg');

            $this->assertGreaterThan($definedGoalsWeightMass['mass_kg'], $operatedWeightMassSum);
            $this->assertTrue($definedGoalsWeightMass['done']);
        }
        // foreach()

        // $materialTypes->each(function ($materialType) use ($response, $ecoDuty) {
        //     foreach ($response->json('data.validated_outgoing_operation_masses') as $validatedOutgoingOperationMass) {
        //         if ($validatedOutgoingOperationMass['id'] === $materialType->id) {
        //             $operationMassValidatedOutgoingSum = OperationMass::where('material_type_id', $materialType->id)
        //                 ->whereIn('eco_membership_id', $ecoDuty->operators->modelKeys())
        //                 ->where('operation_mass_type', OperationMassType::VALIDATED_OUTGOING_WEIGHT)
        //                 ->sum('mass_kg');

        //             //Valida a operation mass (status: OperationMassType::VALIDATED_OUTGOING_WEIGHT)
        //             $this->assertEquals($operationMassValidatedOutgoingSum, $validatedOutgoingOperationMass['mass_kg']);

        //             //Validar massa a comprovar por tipo de material de acordo com a meta definida e a massa total declarada
        //             foreach ($response->json('data.defined_goals_weight_mass') as $definedGoalsWeightMass) {
        //                 if ($definedGoalsWeightMass['id'] === $materialType->id) {

        //                     // Massa declada por eco_duty 
        //                     $liabilityDeclarationSum = LiabilityDeclaration::where('material_type_id', $materialType->id)
        //                         ->where('eco_duty_id', $ecoDuty->id)
        //                         ->sum('mass_kg');

        //                     $definedWeightMassPercentByMaterialType = $response->json('data.defined_goals_percent.' . $materialType->code);

        //                     $totalMassaAComprovar = $liabilityDeclarationSum * $definedWeightMassPercentByMaterialType / 100;

        //                     // valida a quantidade de massa declada de acordo com a meta
        //                     $this->assertEquals($definedGoalsWeightMass['mass_kg'], $totalMassaAComprovar);

        //                     // Valida se a meta foi o atingida 
        //                     if ($validatedOutgoingOperationMass['mass_kg'] > $totalMassaAComprovar)
        //                         $this->assertTrue($definedGoalsWeightMass['done']);
        //                     else
        //                         $this->assertFalse($definedGoalsWeightMass['done']);
        //                 }
        //             }
        //         }
        //     }
        // });
    }

    // public function test_successfully_result_by_operators_with_many_operation_masses()
    // {
    //     $ecoDuty = EcoDuty::factory()->create();

    //     $operatorOne = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
    //         'eco_duty_id' => $ecoDuty->id,
    //     ]);
    //     $operatorTwo = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create([
    //         'eco_duty_id' => $ecoDuty->id,
    //     ]);

    //     $data = [
    //         [
    //             'operator' => $operatorOne->id,
    //             'data' => [
    //                 OperationMassType::VALIDATED_INCOMING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ],
    //                 OperationMassType::VALIDATED_OUTGOING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ]
    //             ]
    //         ],

    //         [
    //             'operator' => $operatorTwo->id,
    //             'data' => [

    //                 OperationMassType::VALIDATED_INCOMING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ],

    //                 OperationMassType::VALIDATED_OUTGOING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ]
    //             ]
    //         ],

    //         [
    //             'operator' => $operatorTwo->id,
    //             'data' => [

    //                 OperationMassType::VALIDATED_INCOMING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ],

    //                 OperationMassType::VALIDATED_OUTGOING_WEIGHT => [
    //                     'quantity' => rand(1, 4),
    //                     'mass_kg' => rand(10, 100),
    //                 ]
    //             ]
    //         ],
    //     ];

    //     foreach ($data as $item) {
    //         foreach ($item['data'] as $key => $value) {
    //             OperationMass::factory($value['quantity'])
    //                 ->create([
    //                     'eco_membership_id' => $item['operator'],
    //                     'operation_mass_type' => $key,
    //                     'mass_kg' => $value['mass_kg']
    //                 ]);
    //         }
    //     }

    //     $response = $this->json('GET', route('eco_duties.quantity_by_operator', $ecoDuty));

    //     $operators = $response->json('data');

    //     foreach ($operators as $operator) {
    //         foreach ($operator['operation_masses'] as $operatorMass) {
    //             $operationMassType = $operatorMass['operation_mass_type'];
    //             $validatedMassTypeTotal = $operatorMass[$operationMassType];

    //             $operationMassKg = 0;

    //             foreach ($data as $dataItem) {
    //                 if ($dataItem['operator'] === $operator['id']) {
    //                     $operationMassKg += $dataItem['data'][$operationMassType]['quantity'] * $dataItem['data'][$operationMassType]['mass_kg'];
    //                 }
    //             }
    //             $this->assertEquals($validatedMassTypeTotal, $operationMassKg);
    //         }
    //     }
    // }

    //TODO
    // public function test_result_by_material_types()
    // {
    //     $materialType = MaterialType::factory()->create();
    //     $materialTypeTwo = MaterialType::factory()->create();

    //     $ecoDuty = EcoDuty::factory()->materialTypes([
    //         $materialType->code => rand(10, 100),
    //         $materialTypeTwo->code => rand(5, 10),
    //     ])->create();

    //     $operatorOne = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create(['eco_duty_id' => $ecoDuty->id]);
    //     $operatorTwo = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create(['eco_duty_id' => $ecoDuty->id]);

    //     $operationMassInputedOperatorOne = OperationMass::factory(rand(2, 5))->type(OperationMassType::INPUTED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorOne->id,
    //         'material_type_id' => $materialType->id
    //     ]);

    //     $operationMassInputedOperatorTwo = OperationMass::factory(rand(2, 5))->type(OperationMassType::INPUTED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorTwo->id,
    //         'material_type_id' => $materialTypeTwo->id
    //     ]);

    //     $operationMassValidadedOperatorOne = OperationMass::factory(rand(2, 5))->type(OperationMassType::ASSESSED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorOne->id,
    //         'material_type_id' => $materialType->id
    //     ]);

    //     $operationMassValidatedOperatorTwo = OperationMass::factory(rand(2, 5))->type(OperationMassType::ASSESSED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorTwo->id,
    //         'material_type_id' => $materialTypeTwo->id
    //     ]);

    //     $response = $this->json('GET', route('eco_duties.quantity_by_material_types', $ecoDuty));

    //     // dd($response->json());
    //     $this->assertTrue(true);
    // }

    // //TODO
    // public function test_result_by_material_types_with_unique_material_and_unique_operator_without_operation_mass_assessed()
    // {
    //     $materialType = MaterialType::factory()->create([
    //         'name' => 'Papel',
    //         'code' => 'paper',
    //     ]);

    //     $ecoDuty = EcoDuty::factory()->materialTypes([
    //         $materialType->code => 22,
    //     ])->create();

    //     $operatorOne = EcoMembership::factory()->role(EcoMembershipRole::OPERATOR())->create(['eco_duty_id' => $ecoDuty->id]);

    //     $operationMassInputedOperatorOne = OperationMass::factory()->type(OperationMassType::INPUTED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorOne->id,
    //         'material_type_id' => $materialType->id,
    //         'mass_kg' => 10
    //     ]);

    //     $operationMassValidadedOperatorOne = OperationMass::factory()->type(OperationMassType::ASSESSED_WEIGHT)->create([
    //         'eco_membership_id' => $operatorOne->id,
    //         'material_type_id' => $materialType->id,
    //         'mass_kg' => 0,
    //     ]);

    //     $response = $this->json('GET', route('eco_duties.quantity_by_material_types', $ecoDuty));
    //     $this->assertTrue(true);

    //     // dd($response->json());
    //     // $result = $response->json();
    //     // foreach ($result as $key => $value) {

    //     //     dd($key, $value);
    //     // }

    //     // $this->assertEquals(2.2,);
    // }
}
