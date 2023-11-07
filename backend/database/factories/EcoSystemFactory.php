<?php

namespace Database\Factories;

use App\Models\EcoSystem;
use App\Models\Location;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoSystemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EcoSystem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location_id' => Location::factory()->create()->id,
            'supervising_organization_id' => Organization::factory()->create()->id,
            'name' => $this->faker->company() . uniqid(),
        ];
    }
}
