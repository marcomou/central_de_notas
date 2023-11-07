<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = collect(json_decode(file_get_contents(database_path('documentTypes.json')), 1));

        $documentTypes->each(function($documentType) {
            DocumentType::updateOrCreate(['code' => $documentType['code']], [
                'name' => $documentType['name'],
                'description' => $documentType['description'],
            ]);
        });
    }
}
