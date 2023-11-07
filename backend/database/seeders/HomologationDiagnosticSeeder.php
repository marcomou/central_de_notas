<?php

namespace Database\Seeders;

use App\Models\EcoMembership;
use App\Models\HomologationDiagnostic;
use Illuminate\Database\Seeder;

class HomologationDiagnosticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HomologationDiagnostic::factory(5)->create();
    }
}
