<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EcoSystem extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'supervising_organization_id',
        'name',
        'location_id'
    ];

    protected $fillable = [
        'supervising_organization_id',
        'name',
        'location_id'
    ];

    protected $with = [
        'location',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function supervisingOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'supervising_organization_id');
    }

    public function ecoRulesets(): HasMany
    {
        return $this->hasMany(EcoRuleset::class);
    }
}
