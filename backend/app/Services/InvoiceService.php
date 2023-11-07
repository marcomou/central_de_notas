<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceService
{
    private OrganizationService $organizationService;

    public function __construct()
    {
        $this->organizationService = new OrganizationService;
    }

    public function createInvoice($nfe)
    {
        $nfeInfo = $nfe['info'];

        $issuer = $this->organizationService->createOperatorIfNotExists($nfe['issuer']);
        $recipient = $this->organizationService->createRecyclerIfNotExists($nfe['recipient']);

        $invoice = Invoice::create([
            'access_key' => $nfe['access_key'],
            'location_code' => $nfeInfo['location_code'],
            'issuer_city_code' => $nfeInfo['issuer_city_code'],
            'invoice_random_number' => $nfeInfo['invoice_random_number'],
            'operation_nature' => $nfeInfo['operation_nature'],
            'payment_indicator' => $nfeInfo['payment_indicator'],
            'fiscal_document_model' => $nfeInfo['fiscal_document_model'],
            'fiscal_document_series' => $nfeInfo['fiscal_document_series'],
            'fiscal_document_number' => $nfeInfo['fiscal_document_number'],
            'operation_type' => $nfeInfo['operation_type'],
            'destiny_identifier' => $nfeInfo['destiny_identifier'],
            'issuing_type' => $nfeInfo['issuing_type'],
            'verifying_digit' => $nfeInfo['verifying_digit'],
            'environmental_type' => $nfeInfo['environmental_type'],
            'issuing_purpose' => $nfeInfo['issuing_purpose'],
            'consumer_indicator' => $nfeInfo['consumer_indicator'],
            'issuing_process' => $nfeInfo['issuing_process'],
            'issued_at' => $nfeInfo['issued_at'],
            'issuer_id' => $issuer ? $issuer->id : null,
            'issuer_name' => $nfe['issuer']['name'],
            'issuer_taxid' => $nfe['issuer']['federal_taxid'],
            'recipient_id' => $recipient ? $recipient->id : null,
            'recipient_name' => $nfe['recipient']['name'],
            'recipient_taxid' => $nfe['recipient']['federal_taxid'],
        ]);

        foreach($nfe['invoice_items'] as $invoiceItem) {
            $this->createInvoiceItem($invoice, $invoiceItem);
        }

        return Invoice::where('access_key', $nfe['access_key'])->first();
    }

    public function createInvoiceItem(Invoice $invoice, $invoiceItem)
    {
        return InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'sequence_invoice_number' => $invoiceItem['sequence_invoice_number'],
            'product_code' => $invoiceItem['product_code'],
            'ean_code' => $invoiceItem['ean_code'],
            'product_description' => $invoiceItem['product_description'],
            'ncm' => $invoiceItem['ncm'],
            'cfop' => $invoiceItem['cfop'],
            'comercial_unit' => $invoiceItem['comercial_unit'],
            'comercial_quantity' => $invoiceItem['comercial_quantity'] ?? 0,
            'comercial_unit_value' => $invoiceItem['comercial_unit_value'] ?? 0,
            'total_gross_value' => $invoiceItem['total_gross_value'] ?? 0,
            'taxable_ean_code' => $invoiceItem['taxable_ean_code'],
            'taxable_unit' => $invoiceItem['taxable_unit'],
            'taxable_quantity' => $invoiceItem['taxable_quantity'] ?? 0,
            'taxable_unit_value' => $invoiceItem['taxable_unit_value'] ?? 0,
            'invoice_value_compound' => $invoiceItem['invoice_value_compound'],
            'icms_icms40_orig' => $invoiceItem['icms_icms40_orig'] ?? 0,
            'icms_icms40_cst' => $invoiceItem['icms_icms40_cst'] ?? 0,
            'ipi_cenq' => $invoiceItem['ipi_cenq'] ?? 0,
            'ipi_ipint_cst' => $invoiceItem['ipi_ipint_cst'] ?? 0,
            'pis_pisaliq_cst' => $invoiceItem['pis_pisaliq_cst'] ?? 0,
            'pis_pisaliq_vbc' => $invoiceItem['pis_pisaliq_vbc'] ?? 0,
            'pis_pisaliq_ppis' => $invoiceItem['pis_pisaliq_ppis'] ?? 0,
            'pis_pisaliq_vpis' => $invoiceItem['pis_pisaliq_vpis'] ?? 0,
            'cofins_cofinsaliq_cst' => $invoiceItem['cofins_cofinsaliq_cst'] ?? 0,
            'cofins_cofinsaliq_vbc' => $invoiceItem['cofins_cofinsaliq_vbc'] ?? 0,
            'cofins_cofinsaliq_pcofins' => $invoiceItem['cofins_cofinsaliq_pcofins'] ?? 0,
            'cofins_cofinsaliq_vcofins' => $invoiceItem['cofins_cofinsaliq_vcofins'] ?? 0,
        ]);
    }
}
