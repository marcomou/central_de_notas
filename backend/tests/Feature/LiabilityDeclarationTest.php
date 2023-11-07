<?php

namespace Tests\Feature;

use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\LiabilityDeclaration;
use App\Models\MaterialType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LiabilityDeclarationTest extends TestCase
{
    use DatabaseMigrations;

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_duty_liability_declaration()
    {
        $data = [
            'eco_duty_id' => EcoDuty::factory()->create()->id,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => rand(1, 100000000)
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('liability_declarations', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_duty_liability_declaration_is_duplicated()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create(['eco_membership_id' => null]);

        $data = [
            'eco_duty_id' => $liabilityDeclaration->eco_duty_id,
            'material_type_id' => $liabilityDeclaration->material_type_id,
            'mass_kg' => rand(1, 100000000)
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('eco_duty_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_store_eco_membership_liability_declaration()
    {
        $ecoMembership = EcoMembership::factory()->create();

        $data = [
            'eco_duty_id' => $ecoMembership->eco_duty_id,
            'eco_membership_id' => $ecoMembership->id,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => rand(1, 100000000)
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('liability_declarations', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return voidW
     */
    public function test_fails_store_eco_membership_liability_declaration_is_duplicated()
    {
        $ecoDuty = EcoDuty::factory()->create();

        $liabilityDeclaration = LiabilityDeclaration::factory()->create([
            'eco_duty_id' => $ecoDuty->id,
            'eco_membership_id' => EcoMembership::factory()->create(['eco_duty_id' => $ecoDuty->id]),
        ]);

        $data = [
            'eco_duty_id' => $liabilityDeclaration->eco_duty_id,
            'material_type_id' => $liabilityDeclaration->material_type_id,
            'eco_membership_id' => $liabilityDeclaration->eco_membership_id,
            'mass_kg' => rand(1, 100000000)
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('eco_membership_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_store_liability_declaration_because_eco_membership_belongs_eco_duty()
    {
        $ecoDuty = EcoDuty::factory()->create();
        $ecoMembership = EcoMembership::factory()->create(['eco_duty_id' => $ecoDuty->id]);

        $data = [
            'eco_duty_id' => $ecoDuty->id,
            'eco_membership_id' => $ecoMembership->id,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => rand(1, 100000),
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('liability_declarations', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_eco_membership_dont_belongs_eco_duty()
    {
        $ecoDuty = EcoDuty::factory()->create();
        $ecoMembership = EcoMembership::factory()->create();

        $this->assertNotSame($ecoMembership->eco_duty_id, $ecoDuty->id);

        $data = [
            'eco_duty_id' => $ecoDuty->id,
            'eco_membership_id' => $ecoMembership->id,
            'material_type_id' => MaterialType::factory()->create()->id,
            'mass_kg' => rand(1, 100000),
        ];

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('eco_membership_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_mass_is_decimal()
    {
        $data = LiabilityDeclaration::factory()->make()->toArray();

        $response = $this->json('POST', route('liability_declarations.store'), array_merge($data, ['mass_kg' => 2443.5]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('mass_kg');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_mass_is_less_than_minimum()
    {
        $data = LiabilityDeclaration::factory()->make()->toArray();

        $response = $this->json('POST', route('liability_declarations.store'), array_merge($data, ['mass_kg' => 0]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('mass_kg');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_eco_duty_is_deleted()
    {
        $ecoDuty = EcoDuty::factory()->create();
        $ecoDuty->delete();

        $data = LiabilityDeclaration::factory()->make(['eco_duty_id' => $ecoDuty->id])->toArray();

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('eco_duty_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_eco_duty_not_exist()
    {
        $data = LiabilityDeclaration::factory()->make(['eco_duty_id' => $this->faker->uuid()])->toArray();

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('eco_duty_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_liability_declaration_because_eco_membership_is_deleted()
    {
        $ecoMembership = EcoMembership::factory()->create();
        $ecoMembership->delete();

        $data = LiabilityDeclaration::factory()->make(['eco_membership_id' => $ecoMembership->id])->toArray();

        $response = $this->json('POST', route('liability_declarations.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('eco_membership_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_show_liability_declaration()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $response = $this->json('GET', route('liability_declarations.show', $liabilityDeclaration));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_liability_declaration_because_is_deleted()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();
        $liabilityDeclaration->delete();

        $response = $this->json('GET', route('liability_declarations.show', $liabilityDeclaration));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_liability_declaration_because_not_exist()
    {
        $response = $this->json('GET', route('liability_declarations.show', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_delete_liability_declaration()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $response = $this->json('DELETE', route('liability_declarations.show', $liabilityDeclaration));

        $response->assertNoContent();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_liability_declaration_because_is_deleted()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();
        $liabilityDeclaration->delete();

        $response = $this->json('DELETE', route('liability_declarations.show', $liabilityDeclaration));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_liability_declaration_because_not_exists()
    {
        $response = $this->json('DELETE', route('liability_declarations.show', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_liability_declaration_material_type()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $data = [
            'mass_kg' => $liabilityDeclaration->mass_kg,
            // 'material_type_id' => MaterialType::factory()->create()->id,
        ];

        $response = $this->json('PUT', route('liability_declarations.update', $liabilityDeclaration), $data);

        $response->assertOk();

        // dd($response->json(), $data);

        $this->assertDatabaseHas('liability_declarations', $data + ['id' => $liabilityDeclaration->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_liability_declaration_mass_kg()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $data = [
            'mass_kg' => $this->faker->numberBetween(),
            'material_type_id' => $liabilityDeclaration->material_type_id,
        ];

        $response = $this->json('PUT', route('liability_declarations.update', $liabilityDeclaration), $data);

        $response->assertOk();

        $this->assertDatabaseHas('liability_declarations', $data + ['id' => $liabilityDeclaration->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_liability_declaration_eco_membership_without_effect()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $data = [
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'mass_kg' => $this->faker->numberBetween(),
            'material_type_id' => $liabilityDeclaration->material_type_id,
        ];

        $response = $this->json('PUT', route('liability_declarations.update', $liabilityDeclaration), $data);

        $response->assertOk();

        $this->assertDatabaseMissing('liability_declarations', $data + ['id' => $liabilityDeclaration->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfully_update_liability_declaration_eco_duty_without_effect()
    {
        $liabilityDeclaration = LiabilityDeclaration::factory()->create();

        $data = [
            'eco_duty_id' => EcoDuty::factory()->create()->id,
            'mass_kg' => $this->faker->numberBetween(),
            'material_type_id' => $liabilityDeclaration->material_type_id,
        ];

        $response = $this->json('PUT', route('liability_declarations.update', $liabilityDeclaration), $data);

        $response->assertOk();

        $this->assertDatabaseMissing('liability_declarations', $data + ['id' => $liabilityDeclaration->id]);
    }
}
