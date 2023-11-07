<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('member_role')->index();
            $table->foreignUuid('eco_duty_id')->index();
            $table->foreignUuid('member_organization_id')->index();
            $table->foreignUuid('through_membership_id')->nullable()->index();
            $table->json('extra')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['member_role', 'eco_duty_id', 'member_organization_id', 'deleted_at'], 'eco_memberships_role_duty_organization_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eco_memberships');
    }
}
