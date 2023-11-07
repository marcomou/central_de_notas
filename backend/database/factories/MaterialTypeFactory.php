<?php

namespace Database\Factories;

use App\Models\MaterialType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->colorName() . uniqid();

        return [
            'name' => $name,
            'code' => snake_case($name)
        ];
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isSubMaterialType()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_material_id' => MaterialType::factory()->create()->id,
            ];
        });
    }
}
