<?php

namespace Database\Factories;

use App\Models\EconomicActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class EconomicActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EconomicActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->numerify('#.###.####'),
            'description' => $this->faker->text(50)
        ];
    }
}
