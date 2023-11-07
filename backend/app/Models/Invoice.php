<?php

namespace App\Models;
use App\Enums\InvoiceStatus;

class Invoice extends Model
{

    protected $fillable = [
        'status',
        'status_reason',
        'access_key',
        'location_code',
        'issuer_city_code',
        'invoice_random_number',
        'operation_nature',
        'payment_indicator',
        'fiscal_document_model',
        'fiscal_document_series',
        'fiscal_document_number',
        'operation_type',
        'destiny_identifier',
        'issuing_type',
        'verifying_digit',
        'environmental_type',
        'issuing_purpose',
        'consumer_indicator',
        'issuing_process',
        'issued_at',
        'issuer_id',
        'issuer_name',
        'issuer_taxid',
        'recipient_id',
        'recipient_name',
        'recipient_taxid',
    ];

    public function isOutput()
    {
        return $this->operation_type == 1;
    }

    public function makeReject(string $reason = null)
    {
        $this->status = InvoiceStatus::REJECTED;
        $this->status_reason = $reason;
    }

    public function makeInvalid(string $reason = null)
    {
        $this->status = InvoiceStatus::INVALID;
        $this->status_reason = $reason;
    }

    public function makeValid()
    {
        $this->status = InvoiceStatus::VALID;
    }

    public function invoiceFiles()
    {
        return $this->belongsToMany(InvoiceFile::class, 'invoice_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function issuer()
    {
        return $this->belongsTo(Organization::class, 'issuer_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Organization::class, 'recipient_id');
    }

}
