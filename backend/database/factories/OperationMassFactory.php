<?php

namespace Database\Factories;

use App\Enums\EcoMembershipRole;
use App\Enums\OperationMassType;
use App\Models\EcoMembership;
use App\Models\MaterialType;
use App\Models\OperationMass;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationMassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OperationMass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_membership_id' => EcoMembership::factory()->role(EcoMembershipRole::OPERATOR)->create()->id,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => $this->faker->numberBetween(10, 100),
            'operation_mass_type' => OperationMassType::getRandomValue(),
            'work_year' => $this->faker->year(),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function type(string $type)
    {
        return $this->state(function (array $attributes) use ($type){
            return [
                'operation_mass_type' => $type,
            ];
        });
    }
}
