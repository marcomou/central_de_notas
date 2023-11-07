<?php

use App\Enums\InvoiceFileStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('content');

            $table->string('file_name');
            $table->string('file_path');
            $table->integer('status')->default(InvoiceFileStatus::PENDING);
            $table->string('status_reason')->nullable();

            $table->foreignUuid('invoice_id')->nullable()->index();
            $table->foreignUuid('sent_by_user_id')->index();
            $table->foreignUuid('sent_by_organization_id')->index();

            $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('invoice_files');
    }
}
