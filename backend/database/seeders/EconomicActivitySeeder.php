<?php

namespace Database\Seeders;

use App\Models\EconomicActivity;
use Illuminate\Database\Seeder;

class EconomicActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $economicActivities = collect(json_decode(file_get_contents(database_path('cnaes.json')), 1));

        $economicActivities->each(function($economicActivity) {
            EconomicActivity::updateOrCreate(['code' => $economicActivity['code']], $economicActivity);
        });
    }
}
