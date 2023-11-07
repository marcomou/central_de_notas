<?php

namespace App\Models;
use App\Enums\InvoiceFileStatus;
use App\Enums\InvoiceFileStatusReason;
use App\Traits\Uploader;

class InvoiceFile extends Model
{

    use Uploader;

    public static $fileFields = [
        'file_path',
    ];
    
    protected $fillable = [
        'file_name',
        'file_path',
        'sent_by_user_id',
        'sent_by_organization_id',
    ];

    public function path(): string
    {
        return $this->getTable();
    }

    public function isCollidence()
    {
        return $this->status == InvoiceFileStatus::COLLIDENCE;
    }

    public function makeValid()
    {
        $this->status = InvoiceFileStatus::VALID;
    }

    public function makeInvalid(string $reason = null)
    {
        $this->status = InvoiceFileStatus::INVALID;
        $this->status_reason = $reason;
    }

    public function makeDuplicated()
    {
        $this->status = InvoiceFileStatus::DUPLICATED;
        $this->status_reason = InvoiceFileStatusReason::FILE_DUPLICATED;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function sentByOrganization()
    {
        return $this->belongsTo(Organization::class, 'sent_by_organization_id');
    }

}
