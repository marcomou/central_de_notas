<?php

namespace Database\Seeders;

use App\Models\EconomicActivity;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationEconomicActivitySeeder extends Seeder
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
            $someEconomicActivities = EconomicActivity::inRandomOrder()->take(rand(1, 5))->get();
            $organization->economicAtivities()->syncWithPivotValues(
                $someEconomicActivities->modelKeys(),
                [
                    'is_primary' => rand(0, 1)
                ]
            );
        });
    }
}
