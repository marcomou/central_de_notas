<?php

namespace Database\Factories;

use App\Enums\AddressSourceTypes;
use App\Models\Address;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organization_id' => Organization::factory()->create()->id,
            'street' => $this->faker->streetName(),
            'number' => $this->faker->randomNumber(2),
            'additional_info' => $this->faker->paragraph(2),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'source' => AddressSourceTypes::getRandomValue(),
        ];
    }

    /**
     * Indicate that the model's address should be one sourced by the Treasury.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function treasurySource()
    {
        return $this->state(function (array $attributes) {
            return [
                'source' => $this->faker->randomElement([
                    AddressSourceTypes::TREASURER_EXTRACTED,
                    AddressSourceTypes::TREASURER_OFFICIALLY_CHECKED,
                ]),
            ];
        });
    }
}
