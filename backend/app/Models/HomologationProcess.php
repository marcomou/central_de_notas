<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HomologationProcess extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'title',
        'process_code',
        'eco_ruleset_id',
        'description',
        'configs',
    ];

    protected $fillable = [
        'title',
        'process_code',
        'eco_ruleset_id',
        'description',
        'configs',
    ];

    protected $casts = [
        'configs' => 'array',
    ];

    public function ecoRuleset(): BelongsTo
    {
        return $this->belongsTo(EcoRuleset::class);
    }

    public function documentTypes(): BelongsToMany
    {
        return $this->belongsToMany(DocumentType::class)
            ->withPivot(['is_mandatory'])
            ->withTimestamps();
    }
}
