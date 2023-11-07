<?php

namespace Database\Seeders;

use App\Models\EcoMembership;
use App\Models\LiabilityDeclaration;
use Illuminate\Database\Seeder;

class LiabilityDeclarationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LiabilityDeclaration::factory(5)->create();
    }
}
