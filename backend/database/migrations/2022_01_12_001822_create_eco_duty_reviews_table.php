<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoDutyReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_duty_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('eco_duty_id')->index();
            $table->unsignedInteger('sequence_number')->index();
            $table->string('type')->index();
            $table->foreignUuid('reviewer_user_id');
            $table->timestamp('reviewed_at');
            $table->string('external_id')->nullable();
            $table->text('comments')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['eco_duty_id', 'sequence_number', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eco_duty_reviews');
    }
}
