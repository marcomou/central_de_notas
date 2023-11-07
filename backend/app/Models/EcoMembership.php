<?php

namespace App\Models;

use App\Enums\EcoMembershipRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EcoMembership extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_duty_id',
        'member_organization_id',
        'through_membership_id',
        'member_role',
    ];

    protected $fillable = [
        'eco_duty_id',
        'member_organization_id',
        'through_membership_id',
        'member_role',
        'extra',
    ];

    protected $casts = [
        'member_role' => EcoMembershipRole::class,
        'extra' => 'array',
        'homologated' => 'boolean',
    ];

    protected $appends = [
        'homologated',
    ];

    protected $with = [
        'throughMembership',
        'memberOrganization',
    ];

    public function getHomologatedAttribute(): bool
    {
        return false;
    }

    public function ecoDuty(): BelongsTo
    {
        return $this->belongsTo(EcoDuty::class);
    }

    public function memberOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'member_organization_id');
    }

    public function throughMembership(): BelongsTo
    {
        return $this->belongsTo(self::class, 'through_membership_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function operationMasses(): HasMany
    {
        return $this->hasMany(OperationMass::class);
    }

    public function homologationDiagnostics(): HasMany
    {
        return $this->hasMany(HomologationDiagnostic::class);
    }

    public function liabilityDeclarations(): HasMany
    {
        return $this->hasMany(LiabilityDeclaration::class);
    }

    public function scopeByMemberRole($query, string $memberRole)
    {
        return $query->where('member_role', $memberRole);
    }

    public function scopeOperators()
    {
        return $this->byMemberRole(EcoMembershipRole::OPERATOR);
    }
}
