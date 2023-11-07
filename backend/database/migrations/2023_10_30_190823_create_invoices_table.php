<?php

use App\Enums\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('status')->default(InvoiceStatus::PENDING);
            $table->string('status_reason')->nullable();

            $table->string('access_key')->unique();
            $table->string('location_code');
            $table->string('issuer_city_code');
            $table->string('invoice_random_number');
            $table->string('operation_nature');
            $table->string('payment_indicator')->nullable();
            $table->string('fiscal_document_model');
            $table->string('fiscal_document_series');
            $table->string('fiscal_document_number');
            $table->string('operation_type');
            $table->string('destiny_identifier');
            $table->string('issuing_type');
            $table->string('verifying_digit');
            $table->string('environmental_type');
            $table->string('issuing_purpose');
            $table->string('consumer_indicator');
            $table->string('issuing_process');
            $table->string('issuer_name');
            $table->string('issuer_taxid');
            $table->string('recipient_name');
            $table->string('recipient_taxid');

            $table->foreignUuid('issuer_id')->nullable()->index();
            $table->foreignUuid('recipient_id')->nullable()->index();

            $table->timestamp('issued_at');
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
        Schema::dropIfExists('invoices');
    }
}
