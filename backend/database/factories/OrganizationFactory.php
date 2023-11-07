<?php

namespace Database\Factories;

use App\Models\LegalType;
use App\Models\Organization;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $organizationName = $this->faker->company;
        
        return [
            'federal_registration' => $this->generateUniqueFederalRegistration(),
            'legal_name' => $organizationName,
            'front_name' => $organizationName,
            'legal_type_id' => LegalType::factory()->create()->id,
            'getherer_id' => $this->faker->uuid(),
        ];
    }

    /**
     * Indicate that the organization is Federal.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function federal()
    {
        return $this->state(function (array $attributes) {
            return [
                'federal_registration' => config('app.federal_registration_mma'),
            ];
        });
    }

    private function generateUniqueFederalRegistration(): string
    {
        $federalRegistration = Utils::generateCnpj();

        if (DB::table('organizations')->where('federal_registration', $federalRegistration)->exists())
            return $this->generateUniqueFederalRegistration();

        return $federalRegistration;
    }
}
