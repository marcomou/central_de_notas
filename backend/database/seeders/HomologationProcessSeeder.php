<?php

namespace Database\Seeders;

use App\Models\EcoRuleset;
use App\Models\HomologationProcess;
use Illuminate\Database\Seeder;

class HomologationProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HomologationProcess::factory(5)->create();
    }
}
