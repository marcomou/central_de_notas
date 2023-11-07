<?php

namespace Database\Factories;

use App\Enums\EcoDutyStatus;
use App\Models\EcoDuty;
use App\Models\EcoRuleset;
use App\Models\MaterialType;
use App\Models\Organization;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoDutyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EcoDuty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_ruleset_id' => EcoRuleset::factory()->create()->id,
            'managing_organization_id' => Organization::factory()->create()->id,
            'status' => EcoDutyStatus::getRandomValue(),
            'managing_code' => uniqid('SIS'),
            'metadata' => [
                'url_name' => 'texto qualquer',
                'url_page' => 'uma url qualquer',
                'description' => $this->faker->sentences(rand(3, 5), true),
                'interloctor' => [
                    'name' => $this->faker->name(),
                    'email' => $this->faker->safeEmail(),
                    'phone' => $this->faker->phoneNumber(),
                    'document' => Utils::generateCpf(),
                    'registration_document' => $this->faker->numerify('#######'),
                ],
                'system_name' => 'Teste Ruan  0332',
                'operational_data' => [
                    'recycling_credit_system' => true,
                    'support_screening_centers' => true,
                    'recycling_credit_system_residual_percent' => [
                        MaterialType::factory()->create()->code => $this->faker->numberBetween(10, 100),
                        MaterialType::factory()->create()->code => $this->faker->numberBetween(10, 100),
                        MaterialType::factory()->create()->code => $this->faker->numberBetween(10, 100),
                        MaterialType::factory()->create()->code => $this->faker->numberBetween(10, 100),
                    ],
                ],
                'residual_object_system' => 'Qualquer resíduo',
            ],
        ];
    }

    public function materialTypes(array $materialTypes)
    {
        return $this->state(function (array $attributes) use ($materialTypes) {
            return [
                'metadata' => [
                    'operational_data' => [
                        'recycling_credit_system_residual_percent' => $materialTypes
                    ]
                ],
            ];
        });
    }
}
