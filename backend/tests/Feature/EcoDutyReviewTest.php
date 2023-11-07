<?php

namespace Tests\Feature;

use App\Enums\EcoDutyReviewType;
use App\Models\EcoDutyReview;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EcoDutyReviewTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_duty_review()
    {
        $data = EcoDutyReview::factory()->make(['type' => EcoDutyReviewType::NOTIFICATIION()])->toArray();

        $response = $this->json('POST', route('eco_duty_reviews.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('eco_duty_reviews', [
            'id' => $response->json('data.id'),
            'eco_duty_id' => $data['eco_duty_id'],
        ]);

        $data = EcoDutyReview::factory()->make(['type' => EcoDutyReviewType::PENDING()])->toArray();

        $response = $this->json('POST', route('eco_duty_reviews.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('eco_duty_reviews', [
            'id' => $response->json('data.id'),
            'eco_duty_id' => $data['eco_duty_id'],
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_duty_review_because_type_unknown()
    {
        $data = EcoDutyReview::factory()->make()->toArray();

        $response = $this->json('POST', route('eco_duty_reviews.store'), array_merge($data, ['type' => 'teste']));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('type');
    }
}
