<?php

namespace Database\Seeders;

use App\Models\OperationMass;
use Illuminate\Database\Seeder;

class OperationMassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OperationMass::factory(10)->create();
    }
}
