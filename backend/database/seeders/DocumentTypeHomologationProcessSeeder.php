<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\HomologationProcess;
use Illuminate\Database\Seeder;

class DocumentTypeHomologationProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homologationProcesses = HomologationProcess::all();

        $homologationProcesses->each(function (HomologationProcess $homologationProcess) {
            $someDocumentTypes = DocumentType::inRandomOrder()->take(rand(2, 5))->get();

            $homologationProcess->documentTypes()->syncWithPivotValues(
                $someDocumentTypes,
                [
                    'is_mandatory' => rand(1, 0)
                ]
            );
        });
    }
}
