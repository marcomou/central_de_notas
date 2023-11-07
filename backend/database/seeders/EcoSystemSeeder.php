<?php

namespace Database\Seeders;

use App\Models\EcoSystem;
use Illuminate\Database\Seeder;

class EcoSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcoSystem::factory(5)->create();
    }
}
