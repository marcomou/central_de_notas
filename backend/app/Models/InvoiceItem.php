<?php

namespace App\Models;
use App\Enums\InvoiceItemStatus;

class InvoiceItem extends Model
{

    public $kgUnits = [
        'kg',
        'kilo',
    ];

    public $tonUnits = [
        'ton',
        'tonelada',
    ];

    protected $fillable = [
        'invoice_id',
        'material_inference_id',
        'material_type_id',
        'infered_is_packaging_source',
        'status',
        'sequence_invoice_number',
        'product_code',
        'ean_code',
        'product_description',
        'ncm',
        'cfop',
        'comercial_unit',
        'comercial_quantity',
        'comercial_unit_value',
        'mass_kg',
        'total_gross_value',
        'taxable_ean_code',
        'taxable_unit',
        'taxable_quantity',
        'taxable_unit_value',
        'invoice_value_compound',
        'icms_icms40_orig',
        'icms_icms40_cst',
        'ipi_cenq',
        'ipi_ipint_cst',
        'pis_pisaliq_cst',
        'pis_pisaliq_vbc',
        'pis_pisaliq_ppis',
        'pis_pisaliq_vpis',
        'cofins_cofinsaliq_cst',
        'cofins_cofinsaliq_vbc',
        'cofins_cofinsaliq_pcofins',
        'cofins_cofinsaliq_vcofins',
    ];

    public function isValid()
    {
        return $this->status === InvoiceItemStatus::VALID;
    }

    public function isKgOrTon()
    {
        return $this->isKg() || $this->isTon();
    }

    public function isTon()
    {
        return $this->checkUnit($this->tonUnits);
    }

    public function isKg()
    {
        return $this->checkUnit($this->kgUnits);
    }

    public function checkUnit(array $units)
    {
        return collect($units)->contains(strtolower($this->comercial_unit ?? ''));
    }

    public function makeInvalid(string $reason = null)
    {
        $this->status = InvoiceItemStatus::INVALID;
        $this->status_reason = $reason;
    }

    public function makeValid()
    {
        $this->status = InvoiceItemStatus::VALID;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

}
