<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LegalTypeSeeder::class,
            EconomicActivitySeeder::class,
            MaterialTypeSeeder::class,
            LocationSeeder::class,
            DocumentTypeSeeder::class,
            OrganizationSeeder::class,
            EcoMembershipSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
