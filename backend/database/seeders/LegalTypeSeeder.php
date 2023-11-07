<?php

namespace Database\Seeders;

use App\Models\LegalType;
use Illuminate\Database\Seeder;

class LegalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Entidade sem fins lucativos',
            'Empresa privada',
            'Fundação',
            'Organização de catadores',
            'Pessoa física',
            'Órgão público',
            'Empresa pública',
            'Sociedade'
        ])->each(function ($legalType) {
            LegalType::updateOrCreate([
                'name' => $legalType
            ]);
        });
    }
}
