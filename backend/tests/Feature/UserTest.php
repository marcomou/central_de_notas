<?php

namespace Tests\Feature;

use App\Models\User;
use App\Utils\Utils;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_lists_users()
    {
        $response = $this->json('GET', route('users.index'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_because_federal_registration_has_same_numbers()
    {
        $response = $this->json('POST', route('users.store'), [
            'federal_registration' => '00000000000',
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
            'password' => 'A!s2d3f4',
            'password_confirmation' => 'A!s2d3f4',
        ]);

        // dd($response->json());

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_because_federal_registration_exists()
    {
        $user = User::factory()->create([
            'federal_registration' => Utils::generateCpf()
        ]);

        $response = $this->json('POST', route('users.store'), [
            'federal_registration' => $user->federal_registration,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
            'password' => 'A!s2d3f4',
            'password_confirmation' => 'A!s2d3f4',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_because_email_exists()
    {
        $user = User::factory()->create([
            'federal_registration' => Utils::generateCpf(),
            'email' => $this->faker->freeEmail(),
        ]);

        $response = $this->json('POST', route('users.store'), [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $user->email,
            'password' => 'A!s2d3f4',
            'password_confirmation' => 'A!s2d3f4',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_store_because_user_is_deleted()
    {
        $user = User::factory()->create([
            'federal_registration' => Utils::generateCpf()
        ]);

        $user->delete();

        $response = $this->json('POST', route('users.store'), [
            'federal_registration' => $user->federal_registration,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
            'password' => 'A!s2d3f4',
            'password_confirmation' => 'A!s2d3f4',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_successfully()
    {
        $data = [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
            'password' => 'A!s2d3f4',
            'password_confirmation' => 'A!s2d3f4',
        ];

        $response = $this->json('POST', route('users.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('users', ['federal_registration' => $data['federal_registration']]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_successfully()
    {
        $user = User::factory()->create();

        $data = [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
        ];

        $response = $this->json('PUT', route('users.update', $user), $data);

        $response->assertOk();

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_update_because_email_exist()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        $data = [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $userTwo->email,
        ];

        $response = $this->json('PUT', route('users.update', $user), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_update_because_email_exist_deleted_at()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();
        $userTwo->delete();

        $data = [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $userTwo->email,
        ];

        $response = $this->json('PUT', route('users.update', $user), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_update_because_user_is_deleted_at()
    {
        $user = User::factory()->create();
        $user->delete();

        $data = [
            'federal_registration' => Utils::generateCpf(),
            'name' => $this->faker->name(),
            'email' => $this->faker->freeEmail(),
        ];

        $response = $this->json('PUT', route('users.update', $user), $data);

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_because_user_is_deleted_at()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->json('DELETE', route('users.destroy', $user));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_because_user_is_deleted_at()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->json('GET', route('users.show', $user));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_delete_because_user_is_not_exist()
    {
        $response = $this->json('DELETE', route('users.destroy', $this->faker->uuid()));

        $response->assertNotFound();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fails_show_because_user_is_not_exist()
    {
        $response = $this->json('GET', route('users.show', $this->faker->uuid()));

        $response->assertNotFound();
    }
}
