<?php

use App\Enums\EcoDutyStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_duties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('managing_code')->unique();
            $table->string('status')->default(EcoDutyStatus::DRAFT)->index();
            $table->foreignUuid('eco_ruleset_id')->index();
            $table->foreignUuid('managing_organization_id')->index();
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('eco_duties');
    }
}
