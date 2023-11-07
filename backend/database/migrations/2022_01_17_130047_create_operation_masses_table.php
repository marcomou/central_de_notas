<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationMassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_masses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('eco_membership_id')->index();
            $table->foreignUuid('material_type_id')->index();
            $table->string('operation_mass_type')->index();
            $table->unsignedBigInteger('mass_kg')->default(0);
            $table->year('work_year')->index();
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
        Schema::dropIfExists('operation_masses');
    }
}
