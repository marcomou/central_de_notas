<?php

namespace Tests\Feature;

use App\Models\EcoSystem;
use App\Models\Location;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EcoSystemTest extends TestCase
{
    use DatabaseMigrations;

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_list_empty_eco_systems()
    {
        $response = $this->json('GET', route('eco_systems.index'));

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_list_eco_systems()
    {
        $quantity = rand(1, 10);

        EcoSystem::factory($quantity)->create();

        $response = $this->json('GET', route('eco_systems.index'));

        $response->assertOk()
            ->assertJsonCount($quantity, 'data');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_store_eco_systems()
    {
        $location = Location::factory()->create();
        $supervisingOrganization = Organization::factory()->create();

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => uniqid('ECO_SYSTEM_')
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertCreated();

        $this->assertTrue($supervisingOrganization->isSupervisingOrganization());

        $this->assertDatabaseHas('eco_systems', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_supervising_organization_is_deleted()
    {
        $location = Location::factory()->create();
        $supervisingOrganization = Organization::factory()->create();
        $supervisingOrganization->delete();

        $this->assertSoftDeleted('organizations', ['id' => $supervisingOrganization->id]);

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => uniqid('ECO_SYSTEM_')
        ];
        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('supervising_organization_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_supervising_organization_not_exists()
    {
        $location = Location::factory()->create();

        $data = [
            'supervising_organization_id' => $this->faker->uuid(),
            'location_id' => $location->id,
            'name' => uniqid('ECO_SYSTEM_')
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('supervising_organization_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_location_not_exists()
    {
        $supervisingOrganization = Organization::factory()->create();

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $this->faker->uuid(),
            'name' => uniqid('ECO_SYSTEM_')
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('location_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_location_is_deleted()
    {
        $supervisingOrganization = Organization::factory()->create();
        $location = Location::factory()->create();
        $location->delete();

        $this->assertSoftDeleted('locations', ['id' => $location->id]);

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => uniqid('ECO_SYSTEM_')
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('location_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_name_exist()
    {
        $supervisingOrganization = Organization::factory()->create();
        $location = Location::factory()->create();
        $location->delete();

        $this->assertSoftDeleted('locations', ['id' => $location->id]);

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => uniqid('ECO_SYSTEM_')
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('location_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_eco_systems_because_name_is_not_unique()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $data = [
            'supervising_organization_id' => $ecoSystem->supervising_organization_id,
            'location_id' => $ecoSystem->location_id,
            'name' => $ecoSystem->name,
        ];

        $response = $this->json('POST', route('eco_systems.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_update_eco_systems_change_name()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $data = [
            'supervising_organization_id' => $ecoSystem->supervising_organization_id,
            'location_id' => $ecoSystem->location_id,
            'name' => $this->faker->colorName(),
        ];

        $response = $this->json('PUT', route('eco_systems.update', $ecoSystem), $data);

        $response->assertOk();

        $this->assertDatabaseHas('eco_systems', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_update_eco_systems_change_location()
    {
        $ecoSystem = EcoSystem::factory()->create();
        $location = Location::factory()->create();

        $data = [
            'supervising_organization_id' => $ecoSystem->supervising_organization_id,
            'location_id' => $location->id,
            'name' => $this->faker->colorName(),
        ];

        $response = $this->json('PUT', route('eco_systems.update', $ecoSystem), $data);

        $response->assertOk();

        $this->assertDatabaseHas('eco_systems', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_update_eco_systems_change_supervising_organization()
    {
        $ecoSystem = EcoSystem::factory()->create();
        $location = Location::factory()->create();
        $supervisingOrganization = Organization::factory()->create();

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => $ecoSystem->name,
        ];

        $response = $this->json('PUT', route('eco_systems.update', $ecoSystem), $data);

        $response->assertOk();

        $this->assertDatabaseHas('eco_systems', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_update_eco_systems_change_name_because_name_exists()
    {
        $otherEcoSystem = EcoSystem::factory()->create();
        $ecoSystem = EcoSystem::factory()->create();
        $location = Location::factory()->create();
        $supervisingOrganization = Organization::factory()->create();

        $data = [
            'supervising_organization_id' => $supervisingOrganization->id,
            'location_id' => $location->id,
            'name' => $otherEcoSystem->name,
        ];

        $response = $this->json('PUT', route('eco_systems.update', $ecoSystem), $data);

        $response->assertUnprocessable()->assertJsonValidationErrors('name');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_delete_eco_system()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('DELETE', route('eco_systems.destroy', $ecoSystem));

        $response->assertNoContent();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_eco_system_because_is_deleted()
    {
        $ecoSystem = EcoSystem::factory()->create();
        $ecoSystem->delete();

        $response = $this->json('DELETE', route('eco_systems.destroy', $ecoSystem));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_eco_system_because_not_exists()
    {
        $response = $this->json('DELETE', route('eco_systems.destroy', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_show_eco_system()
    {
        $ecoSystem = EcoSystem::factory()->create();

        $response = $this->json('GET', route('eco_systems.show', $ecoSystem));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_system_because_is_deleted()
    {
        $ecoSystem = EcoSystem::factory()->create();
        $ecoSystem->delete();

        $response = $this->json('GET', route('eco_systems.show', $ecoSystem));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_system_because_not_exists()
    {
        $response = $this->json('GET', route('eco_systems.show', $this->faker->uuid()));

        $response->assertNotFound();
    }
}
