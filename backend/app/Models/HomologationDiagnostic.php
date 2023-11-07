<?php

namespace App\Models;

use App\Enums\HomologationDiagnosticStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomologationDiagnostic extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_membership_id',
        'homologation_process_id',
        'author_id',
        'annotation',
        'status',
    ];

    protected $fillable = [
        'author_id',
        'eco_membership_id',
        'homologation_process_id',
        'annotation',
        'status',
    ];

    protected $casts = [
        'status' => HomologationDiagnosticStatus::class,
    ];

    protected $with = [
        'homologationProcess',
    ];

    public function homologationProcess(): BelongsTo
    {
        return $this->belongsTo(HomologationProcess::class);
    }

    public function ecoMembership(): BelongsTo
    {
        return $this->belongsTo(EcoMembership::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
