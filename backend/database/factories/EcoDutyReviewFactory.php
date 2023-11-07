<?php

namespace Database\Factories;

use App\Enums\EcoDutyReviewEvent;
use App\Enums\EcoDutyReviewType;
use App\Models\EcoDuty;
use App\Models\EcoDutyReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoDutyReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EcoDutyReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'eco_duty_id' => EcoDuty::factory()->create()->id,
            'reviewer_user_id' => User::factory()->create()->id,
            'type' => EcoDutyReviewType::getRandomValue(),
            'external_id' => $this->faker->boolean() ? $this->faker->uuid() : null,
            'reviewed_at' => $this->faker->dateTime(),
            'comments' => $this->faker->text(),
            'metadata' => $this->faker->sentences(rand(3, 10)),
        ];
    }
}
