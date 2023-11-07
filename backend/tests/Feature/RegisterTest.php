<?php

namespace Tests\Feature;

use App\Models\EcoRuleset;
use App\Models\LegalType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker;

    use DatabaseMigrations;

    const FEDERAL_REGISTRATION_VALID = '39.797.473/0001-05';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_with_federal_registration_invalid()
    {
        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   '12345678901234'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_with_federal_registration_exist()
    {
        $legalType = LegalType::factory()->create();
        Organization::factory()->create([
            'federal_registration' => self::FEDERAL_REGISTRATION_VALID
        ]);

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'password_confirmation' => 'a!s2d3f4',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_with_legal_type_not_exist()
    {
        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $this->faker->uuid()
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('legal_type_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_with_legal_type_deleted()
    {
        $legalType = LegalType::factory()->create();
        $legalType->delete();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('legal_type_id');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_without_user_info()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_is_not_array()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => 'user_data'
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_attributes_are_required()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => null,
                'email' => null,
                'password' => null,
                'federal_registration' => null,
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.name')
            ->assertJsonValidationErrorFor('user.email')
            ->assertJsonValidationErrorFor('user.federal_registration')
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_email_is_not_valid_email()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => 'useremail',
                'password' => null,
                'federal_registration' => null,
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.email');

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => 'useremail@email',
                'password' => null,
                'federal_registration' => null,
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.email');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_successfully_because_user_email_exists_on_database()
    {
        EcoRuleset::factory()->create();
        $legalType = LegalType::factory()->create();
        $user = User::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $user->email,
                'password' => $user->password,
                'password_confirmation' => $user->password,
                'federal_registration' => $user->federal_registration,
            ]
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', ['federal_registration' => $user->federal_registration]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_is_required()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'federal_registration' => '19731865004',
                'password' => null,
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_dont_have_min_lenght()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'pass',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_dont_have_letters()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => '12345678',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_dont_have_numbers()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'asdfghjk',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_dont_have_symbols()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'federal_registration' => '19731865004',
                'password' => 'a1s2d3f4',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_dont_confirmed()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_password_confirmation_dont_same_password()
    {
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'password_confirmation' => 'a@s2d3f4',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrorFor('user.password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_federal_registration_is_invalid()
    {
        EcoRuleset::factory()->create();
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'password_confirmation' => 'a!s2d3f4',
                'federal_registration' => '12345678901',
            ]
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('user.federal_registration');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_fails_because_user_federal_registration_exist()
    {
        EcoRuleset::factory()->create();
        $legalType = LegalType::factory()->create();
        $user = User::factory()->create([
            'federal_registration' => '19731865004',
        ]);

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'password_confirmation' => 'a!s2d3f4',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertUnprocessable();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_successfully()
    {
        EcoRuleset::factory()->create();
        $legalType = LegalType::factory()->create();

        $response = $this->json('POST', route('register'), [
            'federal_registration' =>   self::FEDERAL_REGISTRATION_VALID,
            'legal_name' => $this->faker->company(),
            'front_name' => $this->faker->companySuffix(),
            'legal_type_id' => $legalType->id,
            'user' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->freeEmail(),
                'password' => 'a!s2d3f4',
                'password_confirmation' => 'a!s2d3f4',
                'federal_registration' => '19731865004',
            ]
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', ['federal_registration' => '19731865004']);
    }
}
