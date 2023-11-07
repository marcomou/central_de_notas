<?php

namespace Tests\Feature;

use App\Enums\EcoMembershipRole;
use App\Models\EcoDuty;
use App\Models\EcoMembership;
use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class EcoMembershipTest extends TestCase
{
    use DatabaseMigrations;

    use WithFaker;

    /**
     * Órgão governamental
     */
    private Organization $supervisingOrganization;

    /**
     * Entidade gestora
     */
    private Organization $managingOrganization;

    private EcoDuty $ecoDuty;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->supervisingOrganization = Organization::factory()->create();
        $this->managingOrganization = Organization::factory()->create();

        EcoSystem::factory()->create([
            'supervising_organization_id' => $this->supervisingOrganization->id
        ]);

        $EcoRuleset = EcoRuleset::factory()->create();

        $this->ecoDuty = EcoDuty::factory()->create([
            'managing_organization_id' => $this->managingOrganization->id,
            'eco_ruleset_id' => $EcoRuleset,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_store_eco_membership()
    {
        $organization = Organization::factory()->create();

        $response = $this->json('POST', route('eco_memberships.store'), [
            'member_role' => EcoMembershipRole::getRandomValue(),
            'eco_duty_id' => $this->ecoDuty->id,
            'member_organization_id' => $organization->id,
        ]);

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_store_eco_membership_through_membership()
    {
        $organization = Organization::factory()->create();
        $throughMembership = EcoMembership::factory()->create();

        $response = $this->json('POST', route('eco_memberships.store'), [
            'member_role' => EcoMembershipRole::getRandomValue(),
            'eco_duty_id' => $this->ecoDuty->id,
            'member_organization_id' => $organization->id,
            'through_membership_id' => $throughMembership->id,
        ]);

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_store_eco_membership_extra()
    {
        $organization = Organization::factory()->create();
        $address = [
            'zip_code' => '68903220',
            'city' => 'Macapá',
            'street' => 'Rua Agenor Ferreira Pinto',
            'state' => 'Amapá',
            'number' => '10',
            'complement' => '',
        ];
        $extraDone = [
            'data' => $address
        ];

        $response = $this->json('POST', route('eco_memberships.store'), [
            'member_role' => EcoMembershipRole::getRandomValue(),
            'eco_duty_id' => $this->ecoDuty->id,
            'member_organization_id' => $organization->id,
            'extra' => $address,
        ]);

        $response->assertCreated();
        $this->assertEquals($response->json('data.extra'), $extraDone);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_store_eco_membership_empty_extra()
    {
        $organization = Organization::factory()->create();
        $extraDone = [
            'data' => []
        ];

        $response = $this->json('POST', route('eco_memberships.store'), [
            'member_role' => EcoMembershipRole::getRandomValue(),
            'eco_duty_id' => $this->ecoDuty->id,
            'member_organization_id' => $organization->id,
        ]);

        $response->assertCreated();
        $this->assertEquals($response->json('data.extra'), $extraDone);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_update_eco_membership_extra_data()
    {
        $ecoMembership = EcoMembership::factory()->create(['extra' => [
            'data' => [],
        ]]);
        $address = [
            'zip_code' => '68903220',
            'city' => 'Macapá',
            'street' => 'Rua Agenor Ferreira Pinto',
            'state' => 'Amapá',
            'number' => '10',
            'complement' => '',
        ];
        $extraDone = [
            'data' => $address
        ];

        $response = $this->json('PUT', route('eco_memberships.update', $ecoMembership), [
            'member_role' => $ecoMembership->member_role,
            'eco_duty_id' => $ecoMembership->eco_duty_id,
            'member_organization_id' => $ecoMembership->member_organization_id,
            'extra' => $address
        ]);

        $response->assertOk();
        $this->assertEquals($response->json('data.extra'), $extraDone);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_delete_eco_membership()
    {
        $ecoMembership = EcoMembership::factory()->create();

        $response = $this->json('DELETE', route('eco_memberships.destroy', $ecoMembership));

        $response->assertNoContent();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_successfull_show_eco_membership()
    {
        $ecoMembership = EcoMembership::factory()->create();

        $response = $this->json('GET', route('eco_memberships.show', $ecoMembership));

        $response->assertOk();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_membership_because_is_deleted()
    {
        $ecoMembership = EcoMembership::factory()->create();

        $ecoMembership->delete();

        $response = $this->json('GET', route('eco_memberships.show', $ecoMembership));

        $response->assertNotFound();

        $this->assertSoftDeleted('eco_memberships', ['id' => $ecoMembership->id]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_eco_membership_because_not_exist()
    {
        $ecoMembership = $this->faker->uuid();

        $response = $this->json('GET', route('eco_memberships.show', $ecoMembership));

        $response->assertNotFound();

        $this->assertDatabaseMissing('eco_memberships', ['id' => $ecoMembership]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_show_eco_membership_invoices()
    {
        $ecoMembership = EcoMembership::factory()->create();

        $response = $this->json('GET', route('eco_memberships.invoices', $ecoMembership));

        $response->assertOk();
    }
}
