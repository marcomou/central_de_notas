<?php

namespace Database\Factories;

use App\Enums\ContactType;
use App\Models\Contact;
use App\Models\EcoMembership;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'role' => ContactType::getRandomValue(),
            'name' => $this->faker->name(),
            'document' => Utils::generateCpf(),
            'email' => $this->faker->freeEmail(),
            'phone' => $this->faker->numerify('###########'),
        ];
    }
}
