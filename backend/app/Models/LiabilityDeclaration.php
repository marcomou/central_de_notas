<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiabilityDeclaration extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_membership_id',
        'eco_duty_id',
        'material_type_id',
        'mass_kg',
    ];

    protected $fillable = [
        'eco_duty_id',
        'eco_membership_id',
        'material_type_id',
        'mass_kg',
    ];

    protected $casts = [
        'mass_kg' => 'integer',
    ];

    protected $with = [
        'materialType',
    ];

    public function materialType(): BelongsTo
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function ecoMembership(): BelongsTo
    {
        return $this->belongsTo(EcoMembership::class);
    }

    public function ecoDuty(): BelongsTo
    {
        return $this->belongsTo(EcoDuty::class);
    }
}
