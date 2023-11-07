<?php

namespace Database\Seeders;

use App\Models\EcoDuty;
use Illuminate\Database\Seeder;

class EcoDutySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcoDuty::factory(5)->create();
    }
}
