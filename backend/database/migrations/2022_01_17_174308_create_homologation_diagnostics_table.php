<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomologationDiagnosticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homologation_diagnostics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('homologation_process_id')->index();
            $table->foreignUuid('eco_membership_id')->index();
            $table->foreignUuid('author_id');
            $table->string('status')->index();
            $table->text('annotation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homologation_diagnostics');
    }
}
