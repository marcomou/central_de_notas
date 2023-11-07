<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->generateCode(),
            'name' => $this->faker->state(),
            'acronym' => $this->faker->colorName(),
            'region' => $this->faker->colorName(),
        ];
    }

    private function generateCode(): int
    {
        $code = rand(1, 100);

        if (DB::table('locations')->where('code', $code)->exists())
            return $this->generateCode();

        return $code;
    }
}
