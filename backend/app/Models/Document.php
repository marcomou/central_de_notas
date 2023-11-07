<?php

namespace App\Models;

use App\Traits\Uploader;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use Uploader;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'uploader_user_id',
        'document_type_id',
        'eco_membership_id',
        'file_path',
        'external_service',
        'external_id',
        'annotation',
        'metadata',
    ];

    protected $fillable = [
        'uploader_user_id',
        'document_type_id',
        'eco_membership_id',
        'file_name',
        'file_path',
        'annotation',
        'metadata',
    ];

    public static $fileFields = [
        'file_path',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $with = [
        'documentType',
    ];

    protected $appends = [
        'url',
    ];

    public function path(): string
    {
        return $this->getTable();
    }

    public function getUrlAttribute(): ?string
    {
        return $this->getUrl($this->file_path);
    }

    public function uploaderUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function ecoMembership(): BelongsTo
    {
        return $this->belongsTo(EcoMembership::class);
    }
}
