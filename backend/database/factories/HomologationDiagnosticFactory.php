<?php

namespace Database\Factories;

use App\Enums\HomologationDiagnosticStatus;
use App\Models\EcoMembership;
use App\Models\HomologationDiagnostic;
use App\Models\HomologationProcess;
use App\Models\OperationMass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomologationDiagnosticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomologationDiagnostic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'homologation_process_id' => HomologationProcess::factory()->create()->id,
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'author_id' => User::factory()->create()->id,
            'annotation' => $this->faker->sentences(rand(1, 4), true),
            'status' => HomologationDiagnosticStatus::getRandomValue()
        ];
    }
}
