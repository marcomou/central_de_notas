<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_unsupported_grant_type()
    {
        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'incorrect',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['error', 'error_description', 'message']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_invalid_client_because_not_exist()
    {
        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'user@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['error', 'error_description', 'message']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_invalid_client_because_is_not_password()
    {
        Artisan::call('passport:install');

        $client = Client::where('password_client', 0)->first();

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => 'user@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['error', 'error_description', 'message']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_invalid_request()
    {
        Artisan::call('passport:install');

        $client = Client::where('password_client', 1)->first();
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'senha' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'email' => $user->email,
            'senha' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_invalid_credentials()
    {
        Artisan::call('passport:install');

        $client = Client::where('password_client', 1)->first();

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => 'user@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(['error', 'error_description', 'message']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_password_authentication_successfully()
    {
        Artisan::call('passport:install');

        $client = Client::where('password_client', 1)->first();
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }
}
