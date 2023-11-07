<?php

namespace Database\Factories;

use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoRulesetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EcoRuleset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_system_id' => EcoSystem::factory()->create()->id,
            'duty_year' => $this->faker->year(),
            'rules' => $this->faker->words()
        ];
    }
}
