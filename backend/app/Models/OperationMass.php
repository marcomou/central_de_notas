<?php

namespace App\Models;

use App\Enums\OperationMassType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationMass extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_membership_id',
        'material_type_id',
        'mass_kg',
        'work_year',
        'operation_mass_type',
    ];

    protected $fillable = [
        'eco_membership_id',
        'material_type_id',
        'mass_kg',
        'work_year',
        'operation_mass_type'
    ];

    protected $casts = [
        'mass_kg' => 'integer',
        'operation_mass_type' => OperationMassType::class,
    ];

    protected $with = [
        'materialType',
    ];

    public function ecoMembership(): BelongsTo
    {
        return $this->belongsTo(EcoMembership::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(EcoMembership::class, 'eco_membership_id');
    }

    public function materialType(): BelongsTo
    {
        return $this->belongsTo(MaterialType::class);
    }
}
