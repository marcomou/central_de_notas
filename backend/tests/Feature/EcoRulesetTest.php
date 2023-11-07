<?php

namespace Tests\Feature;

use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EcoRulesetTest extends TestCase
{
    use WithFaker;

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_list_paginated_eco_rulesets()
    {
        $response = $this->json('GET', route('eco_rulesets.index'));

        $response->assertOk()->assertJsonStructure(['data', 'meta', 'links']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_show_eco_rulesets()
    {
        $ruleset = EcoRuleset::factory()->create();

        $response = $this->json('GET', route('eco_rulesets.show', $ruleset));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_rulesets_not_exists()
    {
        $response = $this->json('GET', route('eco_rulesets.show', $this->faker->uuid));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_rulesets_deleted()
    {
        $ruleset = EcoRuleset::factory()->create();
        $ruleset->delete();

        $response = $this->json('GET', route('eco_rulesets.show', $ruleset));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_eco_system_is_deleted()
    {
        $ecoSystem = EcoSystem::factory()->create();
        $ecoSystem->delete();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => $this->faker->year(),
            'rules' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'eco_system_id',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_eco_system_not_exists()
    {
        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $this->faker->uuid(),
            'duty_year' => $this->faker->year(),
            'rules' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'eco_system_id',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_duty_year_is_not_year_valid()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => 'AAAA',
            'rules' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'duty_year',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_duty_year_is_greather_than_the_next_year()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => date('Y') + 2,
            'rules' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'duty_year',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_duty_year_is_less_than_1900()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => 1899,
            'rules' => [],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'duty_year',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_rulesets_because_rules_is_not_array()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => $this->faker->year(date('Y') + 1),
            'rules' => $this->faker->colorName(),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'rules',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_rulesets()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('POST', route('eco_rulesets.store'), [
            'eco_system_id' => $ecoSystem->id,
            'duty_year' => $this->faker->year(date('Y') + 1),
            'rules' => [
                'object' => 'value',
                'other_object' => [
                    'child_object' => 'value'
                ]
            ],
        ]);

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_delete_eco_rulesets()
    {
        $ecoRuleset = EcoRuleset::factory()->create();

        $response = $this->json('DELETE', route('eco_rulesets.destroy', $ecoRuleset));

        $response->assertNoContent();
        $this->assertSoftDeleted($ecoRuleset->getTable(), ['id' => $ecoRuleset->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_eco_rulesets_because_eco_ruleset_is_deleted()
    {
        $ecoRuleset = EcoRuleset::factory()->create();
        $ecoRuleset->delete();

        $response = $this->json('DELETE', route('eco_rulesets.destroy', $ecoRuleset));

        $response->assertNotFound();
        $this->assertSoftDeleted($ecoRuleset->getTable(), ['id' => $ecoRuleset->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_eco_rulesets_because_eco_ruleset_not_exists()
    {
        $ecoRuleset = EcoRuleset::factory()->create();

        $response = $this->json('PUT', route('eco_rulesets.update', $ecoRuleset), [
            'eco_system_id' => $ecoRuleset->eco_system_id,
            'duty_year' => $ecoRuleset->duty_year + 1,
            'rules' => [
                'object' => 'value',
                'other_object' => [
                    'child_object' => 'value'
                ]
            ],
        ]);

        $response->assertOk();
    }
}
