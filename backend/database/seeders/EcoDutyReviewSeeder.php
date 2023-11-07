<?php

namespace Database\Seeders;

use App\Models\EcoDuty;
use App\Models\EcoDutyReview;
use Illuminate\Database\Seeder;

class EcoDutyReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcoDutyReview::factory(5)->create();
    }
}
