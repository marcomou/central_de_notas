<?php

namespace Database\Factories;

use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\LiabilityDeclaration;
use App\Models\MaterialType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiabilityDeclarationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LiabilityDeclaration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isPerMember = $this->faker->boolean();
        $ecoDuty = EcoDuty::factory()->create();

        return [
            'eco_duty_id' => $ecoDuty->id,
            'eco_membership_id' => $isPerMember ? EcoMembership::factory()->create(['eco_duty_id' => $ecoDuty->id])->id : null,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => $this->faker->numberBetween(0, 100),
        ];
    }
}
