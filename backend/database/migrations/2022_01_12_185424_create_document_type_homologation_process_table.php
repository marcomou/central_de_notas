<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTypeHomologationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_type_homologation_process', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('document_type_id');
            $table->foreignUuid('homologation_process_id')->index();
            $table->boolean('is_mandatory');
            $table->string('member_role')->nullable();
            $table->foreignUuid('legal_type_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_type_homologation_process');
    }
}
