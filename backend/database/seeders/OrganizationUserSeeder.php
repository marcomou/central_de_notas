<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::all();

        $organizations->each(function (Organization $organization) {
            $someUsers = User::inRandomOrder()->take(rand(1, 2))->get();
            $organization->users()->sync($someUsers);
        });
    }
}
