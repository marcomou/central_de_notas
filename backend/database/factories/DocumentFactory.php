<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EcoMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isExternal = $this->faker->boolean();

        $fileFake = $this->generateFakeFile();

        return [
            'uploader_user_id' => User::factory()->create()->id,
            'document_type_id' => DocumentType::factory()->create()->id,
            'eco_membership_id' => EcoMembership::factory()->create()->id,
            'external_service' => $isExternal ? $this->faker->word() : null,
            'external_id' => $isExternal ? $this->faker->uuid() : null,
            'file_name' => $fileFake->getClientOriginalName(),
            'file_path' => $fileFake->hashName(),
            'annotation' => $this->faker->boolean() ? $this->faker->sentences(rand(2, 6), true) :  null,
            'metadata' => $this->faker->boolean() ? $this->faker->sentences(rand(2, 6)) : [],
        ];
    }

    private function generateFakeFile(): UploadedFile
    {
        $fileFake = UploadedFile::fake()->create('teste-update.pdf', 10);

        Storage::put('documents', $fileFake);

        return $fileFake;
    }
}
