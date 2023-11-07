<?php

namespace App\Models;

use App\Enums\EcoDutyReviewType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoDutyReview extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_duty_id',
        'sequence_number',
        'reviewer_user_id',
        'type',
        'reviewed_at',
        'external_id',
        'comments',
        'metadata',
    ];

    protected $fillable = [
        'eco_duty_id',
        'sequence_number',
        'reviewer_user_id',
        'type',
        'reviewed_at',
        'external_id',
        'comments',
        'metadata',
    ];

    protected $casts = [
        'sequence_number' => 'integer',
        'metadata' => 'array',
        'type' => EcoDutyReviewType::class,
    ];

    protected $dates = [
        'reviewed_at',
    ];

    protected $with = [
        'reviewer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sequence_number = self::where('eco_duty_id', $model->eco_duty_id)->max('sequence_number') + 1;
        });
    }

    public function ecoDuty(): BelongsTo
    {
        return $this->belongsTo(EcoDuty::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_user_id');
    }
}
