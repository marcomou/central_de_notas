<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('uploader_user_id');
            $table->foreignUuid('document_type_id')->index();
            $table->foreignUuid('eco_membership_id')->index();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('external_service')->nullable();
            $table->string('external_id')->nullable();
            $table->json('metadata')->nullable();
            $table->text('annotation')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['external_service', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
