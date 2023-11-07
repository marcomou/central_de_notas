<?php

namespace Database\Seeders;

use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\LiabilityDeclaration;
use App\Models\MaterialType;
use App\Models\OperationMass;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class EcoMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ecoDuties = EcoDuty::all();

        foreach (EcoMembershipRole::asArray() as $ecoMembershipRole) {
            $ecoDuties->each(function (EcoDuty $ecoDuty) use ($ecoMembershipRole) {

                $quantity = rand(2, 5);

                for ($i = 0; $i < $quantity; $i++) {

                    $data = [
                        'member_role' => $ecoMembershipRole,
                        'eco_duty_id' => $ecoDuty->id,
                        'member_organization_id' => Organization::factory()->create()->id,
                        'through_membership_id' => null,
                        'extra' => [],
                    ];

                    switch ($ecoMembershipRole) {
                        case EcoMembershipRole::OPERATOR:
                            $this->createOperator($data);
                            break;

                        case EcoMembershipRole::LIABLE:
                            $this->createLiable($data);
                            break;

                        case EcoMembershipRole::RECYCLER:
                            $this->createRecycler($data);
                            break;

                        case EcoMembershipRole::INTERMEDIATE:
                            $this->createIntermediator($data);
                            break;

                        default:
                            break;
                    }
                }
            });
        }
    }

    private function createIntermediator(array $data)
    {
        $intermediator = EcoMembership::create($data);

        // Criar documents

        return $intermediator;
    }


    private function createRecycler(array $data): EcoMembership
    {
        $recycler = EcoMembership::create($data);

        // Criar documents

        return $recycler;
    }

    private function createLiable(array $data): EcoMembership
    {
        $liable = EcoMembership::create($data);

        // Criar documents

        // MaterialType::all()
        //     ->each(function ($materialType) use ($liable) {
        //         LiabilityDeclaration::updateOrCreate([
        //             'eco_membership_id' => $liable->id,
        //             'material_type_id' => $materialType->id,
        //             'eco_duty_id' => $liable->eco_duty_id,
        //             'mass_kg' => rand(100, 200),
        //         ]);
        //     });

        return $liable;
    }

    private function createOperator(array $data): EcoMembership
    {
        $operator = EcoMembership::create($data);

        // Documentos

        // Homologação

        // Notas fiscais

        $ecoDuty = EcoDuty::find($data['eco_duty_id']);

        MaterialType::whereIn('code', $ecoDuty->material_types_keys)
            ->get()
            ->each(function ($materialType) use ($operator) {
                foreach (OperationMassType::asArray() as $operationMassType) {
                    OperationMass::updateOrCreate([
                        'eco_membership_id' => $operator->id,
                        'material_type_id' => $materialType->id,
                        'operation_mass_type' => $operationMassType,
                    ], [
                        'mass_kg' => rand(2200, 30000),
                        'work_year' => rand(2019, 2021),
                    ]);
                }
            });

        return $operator;
    }
}
