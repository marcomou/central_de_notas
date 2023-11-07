<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEconomicActivityOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('economic_activity_organization', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('economic_activity_id');
            $table->foreignUuid('organization_id')->index();
            $table->boolean('is_primary')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('economic_activity_organization');
    }
}
