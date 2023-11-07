<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::factory(5)->create();
    }
}
