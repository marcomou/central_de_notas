<?php

namespace Database\Seeders;

use App\Models\EcoRuleset;
use App\Models\EcoSystem;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class EcoRulesetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcoRuleset::factory(5)->create();
    }
}
