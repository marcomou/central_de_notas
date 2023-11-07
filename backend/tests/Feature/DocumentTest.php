<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EcoMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('filesystem.default', 's3');

        $defaultPath = (new Document)->path();

        $files = Storage::allFiles($defaultPath);

        Storage::delete($files);
        Storage::deleteDirectory($defaultPath);
    }

    /**
     * test create document test.
     *
     * @return void
     */
    public function test_successfully_store_document()
    {
        $documentType = DocumentType::factory()->create();
        $ecoMembership = EcoMembership::factory()->create();
        $uploaderUser = User::factory()->create();
        $file = UploadedFile::fake()->create('teste.pdf', 100);

        $response = $this->json('POST', route('documents.store'), [
            "uploader_user_id" => $uploaderUser->id,
            "document_type_id" => $documentType->id,
            "eco_membership_id" => $ecoMembership->id,
            // "name" => "Teste upload",
            "file_path" => $file,
        ]);

        $response->assertCreated();
    }

    /**
     * test create document with file_path string test.
     *
     * @return void
     */
    public function test_fails_store_document_because_file_is_string()
    {
        $documentType = DocumentType::factory()->create();
        $ecoMembership = EcoMembership::factory()->create();
        $uploaderUser = User::factory()->create();
        $file = 'teste.pdf';

        $response = $this->json('POST', route('documents.store'), [
            "uploader_user_id" => $uploaderUser->id,
            "document_type_id" => $documentType->id,
            "eco_membership_id" => $ecoMembership->id,
            // "name" => "Teste upload",
            "file_path" => $file,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('file_path');
    }

    /**
     * test create document test.
     *
     * @return void
     */
    public function test_successfully_update_document_setting_new_file()
    {
        $document = Document::factory()->create();
        $newfile = UploadedFile::fake()->create('teste-update.pdf', 100);

        $response = $this->json('PUT', route('documents.update', $document), [
            "uploader_user_id" => $document->uploader_user_id,
            "document_type_id" => $document->document_type_id,
            "eco_membership_id" => $document->eco_membership_id,
            // "name" => "Teste upload document setting new file",
            "file_path" => $newfile,
        ]);

        $response->assertOk();

        $this->assertEquals($newfile->hashName(), $response->json('data.file_path'));
    }

    /**
     * test create update test.
     *
     * @return void
     */
    public function test_update_document_without_change_file()
    {
        $document = Document::factory()->create();

        $response = $this->json('PUT', route('documents.update', $document), [
            "uploader_user_id" => $document->uploader_user_id,
            "document_type_id" => $document->document_type_id,
            "eco_membership_id" => $document->eco_membership_id,
            // "name" => "Teste upload document without change file",
            "file_path" => $document->file_path,
        ]);

        $response->assertOk();
        $this->assertEquals($document->file_path, $response->json('data.file_path'));
    }
}
