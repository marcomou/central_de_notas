<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationUserTest extends TestCase
{
    use DatabaseMigrations;

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_succesfully_attach_organization_user()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();

        $response = $this->json('POST', route('organizations.users.attach', [$organization, $user]));

        $response->assertOk();

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_succesfully_detach_organization_user()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();

        $organization->users()->attach($user);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ]);

        $response = $this->json('DELETE', route('organizations.users.detach', [$organization, $user]));

        $response->assertOk();

        $this->assertDatabaseMissing('organization_user', [
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_attach_organization_user_because_organization_no_exist()
    {
        $organization = $this->faker->uuid();
        $user = User::factory()->create();

        $response = $this->json('POST', route('organizations.users.attach', [$organization, $user]));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_attach_organization_user_because_user_no_exist()
    {
        $organization = User::factory()->create();
        $user = $this->faker->uuid();

        $response = $this->json('POST', route('organizations.users.attach', [$organization, $user]));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_detach_organization_user_because_user_no_exist()
    {
        $organization = Organization::factory()->create();
        $user = $this->faker->uuid();

        $response = $this->json('DELETE', route('organizations.users.detach', [$organization, $user]));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_detach_organization_user_because_user_is_deleted()
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();
        
        $organization->users()->attach($user);

        $user->delete();

        $response = $this->json('DELETE', route('organizations.users.detach', [$organization, $user]));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_detach_organization_user_because_user_not_attached_with_organization()
    {
        $organizationOne = Organization::factory()->create();
        $userOneOrganizationOne = User::factory()->create();
        $userTwoOrganizationOne = User::factory()->create();

        $organizationOne->users()->attach([
            $userOneOrganizationOne->id,
            $userTwoOrganizationOne->id,
        ]);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $userOneOrganizationOne->id,
            'organization_id' => $organizationOne->id,
        ]);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $userTwoOrganizationOne->id,
            'organization_id' => $organizationOne->id,
        ]);

        // ====

        $organizationTwo = Organization::factory()->create();
        $userOneOrganizationTwo = User::factory()->create();
        $userTwoOrganizationTwo = User::factory()->create();

        $organizationTwo->users()->attach([
            $userOneOrganizationTwo->id,
            $userTwoOrganizationTwo->id,
        ]);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $userTwoOrganizationTwo->id,
            'organization_id' => $organizationTwo->id,
        ]);

        $this->assertDatabaseHas('organization_user', [
            'user_id' => $userTwoOrganizationTwo->id,
            'organization_id' => $organizationTwo->id,
        ]);

        // dd($organizationTwo->id, $organizationTwo->users->modelKeys(), $organizationOne->id, $organizationOne->users->modelKeys());

        $response = $this->json(
            'DELETE',
            route('organizations.users.detach', [
                $organizationOne,
                array_random($organizationTwo->users->modelKeys())
            ])
        );

        // dd($response->json());

        $response->assertNotFound();
    }
}
