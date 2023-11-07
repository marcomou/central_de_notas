<?php

use App\Enums\InvoiceItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('status')->default(InvoiceItemStatus::PENDING);
            $table->string('status_reason')->nullable();

            $table->string('sequence_invoice_number');
            $table->string('product_code');
            $table->string('ean_code');
            $table->string('product_description');
            $table->string('ncm');
            $table->string('cfop');

            $table->string('comercial_unit');
            $table->float('comercial_quantity');
            $table->float('comercial_unit_value');
            $table->integer('mass_kg')->default(0);

            $table->float('total_gross_value');

            $table->string('taxable_ean_code');
            $table->string('taxable_unit');
            $table->float('taxable_quantity');
            $table->float('taxable_unit_value');
            $table->string('invoice_value_compound');

            $table->float('icms_icms40_orig');
            $table->float('icms_icms40_cst');
            $table->float('ipi_cenq');
            $table->float('ipi_ipint_cst');
            $table->float('pis_pisaliq_cst');
            $table->float('pis_pisaliq_vbc');
            $table->float('pis_pisaliq_ppis');
            $table->float('pis_pisaliq_vpis');
            $table->float('cofins_cofinsaliq_cst');
            $table->float('cofins_cofinsaliq_vbc');
            $table->float('cofins_cofinsaliq_pcofins');
            $table->float('cofins_cofinsaliq_vcofins');

            $table->foreignUuid('invoice_id')->index();
            $table->foreignUuid('material_inference_id')->nullable()->index();
            $table->foreignUuid('material_type_id')->nullable()->index();
            $table->boolean('infered_is_packaging_source')->default(false)->index();

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
        Schema::dropIfExists('invoice_items');
    }
}
