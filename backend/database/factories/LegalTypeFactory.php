<?php

namespace Database\Factories;

use App\Models\LegalType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegalTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LegalType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->colorName
        ];
    }
}
