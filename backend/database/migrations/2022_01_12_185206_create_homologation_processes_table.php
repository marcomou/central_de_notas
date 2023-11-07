<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomologationProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homologation_processes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('process_code')->unique();
            $table->foreignUuid('eco_ruleset_id')->index();
            $table->text('description')->nullable();
            $table->json('configs')->nullable();
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
        Schema::dropIfExists('homologation_processes');
    }
}
