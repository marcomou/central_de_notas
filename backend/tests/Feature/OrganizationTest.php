<?php

namespace Tests\Feature;

use App\Models\LegalType;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_organization_create_successfully()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('organizations.store'), [
            'federal_registration' => '93953574000165',
            'legal_name' => $this->faker->colorName() . ' ' . $this->faker->companySuffix(),
            'front_name' => $this->faker->colorName(),
            'legal_type_id' => $legalType->id,
        ]);

        $response->assertCreated();
    }

    public function test_organization_update_successfully()
    {
        $organization = Organization::factory()->create();

        $data = [
            'legal_name' => 'Legal name Updated',
            'front_name' => 'Front name updated',
        ];

        $response = $this->json('PUT', route('organizations.update', $organization), $data);

        $response->assertOk();
        $this->assertDatabaseHas('organizations', [
            'federal_registration' => $organization->federal_registration
        ] + $data);
    }

    public function test_fails_organization_update_federal_registration()
    {
        $organization = Organization::factory()->create();

        $data = [
            'legal_name' => 'Legal name Updated',
            'front_name' => 'Front name updated',
            'federal_registration' => '93953574000165'
        ];

        $response = $this->json('PUT', route('organizations.update', $organization), $data);

        $response->assertOk();

        $this->assertDatabaseMissing('organizations', [
            'federal_registration' => $data['federal_registration']
        ]);

        $this->assertDatabaseHas('organizations', [
            'federal_registration' => $organization->federal_registration,
            'legal_name' => $data['legal_name'],
            'front_name' => $data['front_name'],
        ]);
    }

    public function test_organization_delete_successfully()
    {
        $organization = Organization::factory()->create();

        $response = $this->json('DELETE', route('organizations.destroy', $organization));

        $response->assertNoContent();

        $this->assertSoftDeleted('organizations', [
            'federal_registration' => $organization->federal_registration,
        ]);
    }

    public function test_fails_organization_delete_because_no_exist()
    {
        $response = $this->json('DELETE', route('organizations.destroy', $this->faker->uuid()));

        $response->assertNotFound();
    }

    public function test_fails_organization_delete_because_is_deleted()
    {
        $organization = Organization::factory()->create();
        $organization->delete();

        $response = $this->json('DELETE', route('organizations.destroy', $organization));

        $response->assertNotFound();
        $this->assertSoftDeleted('organizations', [
            'federal_registration' => $organization->federal_registration,
        ]);
    }
}
