<?php

namespace Database\Factories;

use App\Models\EcoRuleset;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomologationProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_ruleset_id' => EcoRuleset::factory()->create()->id,
            'title' => $this->faker->sentence(),
            'process_code' => snake_case($this->faker->words(3, true)) . '_' . uniqid(),
            'description' => $this->faker->sentences(rand(2, 5), true),
            'configs' => [],
        ];
    }
}
