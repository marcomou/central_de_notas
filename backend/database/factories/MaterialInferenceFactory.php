<?php

namespace Database\Factories;

use App\Models\EcoRuleset;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialInferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentences(rand(2, 5), true),
            'material_type_id' => uniqid(),
            'is_packaging_source' => $this->faker->boolean(),
        ];
    }
}
