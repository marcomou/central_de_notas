<?php

namespace Database\Factories;

use App\Enums\EcoMembershipRole;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoMembershipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EcoMembership::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'member_role' => EcoMembershipRole::getRandomValue(),
            'eco_duty_id' => EcoDuty::factory()->create()->id,
            'member_organization_id' => Organization::factory()->create()->id,
            'through_membership_id' => null,
            'extra' => null,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function role(string $role)
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'member_role' => $role,
            ];
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ecoDuty(string $ecoDuty)
    {
        return $this->state(function (array $attributes) use ($ecoDuty) {
            return [
                'eco_duty_id' => $ecoDuty,
            ];
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function throughMembership(string $throughMembershipId)
    {
        return $this->state(function (array $attributes) use ($throughMembershipId) {
            return [
                'through_membership_id' => $throughMembershipId,
            ];
        });
    }
}
