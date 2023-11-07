<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utils\Utils;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(5)->create();

        $users = collect([
            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Jonatan',
                'email' => 'jonathan@inundaweb.com.br',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Eduardo Cavalcante',
                'email' => 'eduardo.cavalcante@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Eduardo Andrade',
                'email' => 'eduardo.andrade@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Ruan Vinicius',
                'email' => 'ruan.vinicius@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Sabrina Andrade',
                'email' => 'sabrina@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Mislene Félix',
                'email' => 'mislene.felix@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Élcio Ferreira',
                'email' => 'elcio@nhecotech.com',
                'password' => 'password'
            ],

            [
                'federal_registration' => Utils::generateCpf(),
                'name' => 'Leonardo Berbare',
                'email' => 'leonardo@nhecotech.com',
                'password' => 'password'
            ],
        ]);

        $users->each(function ($user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        });
    }
}
