<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiabilityDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liability_declarations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('eco_membership_id')->index()->nullable();
            $table->foreignUuid('eco_duty_id')->index();
            $table->foreignUuid('material_type_id')->index();
            $table->unsignedBigInteger('mass_kg');
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
        Schema::dropIfExists('liability_declarations');
    }
}
