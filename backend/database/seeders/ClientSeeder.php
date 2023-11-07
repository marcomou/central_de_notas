<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oauth_clients = array(
            array('id' => '95a1056e-f76e-4605-b41f-c44d5f439800', 'user_id' => NULL, 'name' => 'SisLog Personal Access Client', 'secret' => 'qnNQSTkcFW8MsI6OIADwltpab20o8fvpjjEJwSNi', 'provider' => NULL, 'redirect' => 'http://localhost', 'personal_access_client' => '1', 'password_client' => '0', 'revoked' => '0', 'created_at' => '2022-02-18 12:42:14', 'updated_at' => '2022-02-18 12:42:14'),
            array('id' => '95a10572-f420-4c21-a7f7-5133b8cf6266', 'user_id' => NULL, 'name' => 'SisLog Password Grant Client', 'secret' => 'enHTR9YKMwNCOgfaQo0rRR8RYrvAltd03svixkZ4', 'provider' => 'users', 'redirect' => 'http://localhost', 'personal_access_client' => '0', 'password_client' => '1', 'revoked' => '0', 'created_at' => '2022-02-18 12:42:16', 'updated_at' => '2022-02-18 12:42:16')
        );

        DB::table('oauth_clients')->insert($oauth_clients);
    }
}
