<?php

namespace Database\Seeders;

use App\Models\MaterialInference;
use App\Models\MaterialType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialInferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $materialTypes = [];
        MaterialType::all()->each(function ($materialType) use (&$materialTypes) {
            $materialTypes[strtolower($materialType->name)] = $materialType;
        });

        $fileHandler = fopen(database_path('material_inferences.txt'), 'r');

        while ($line = fgets($fileHandler)) {
            $info = explode("\t", $line);
            $materialName = mb_strtolower(trim($info[1]));

            if (!array_key_exists($materialName, $materialTypes)) {
                continue;
            }

            $materialType = $materialTypes[$materialName];

            MaterialInference::updateOrCreate(['description' => $info[0]], [
                'material_type_id' => $materialType->id,
                'is_packaging_source' => mb_strtolower(trim($info[2])) === 'sim',
            ]);
        }
        fclose($fileHandler);
    }
}
