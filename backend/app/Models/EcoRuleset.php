<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoRuleset extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'eco_system_id',
        'duty_year',
        'rules',
    ];

    protected $fillable = [
        'eco_system_id',
        'duty_year',
        'rules',
    ];

    protected $casts = [
        'rules' => 'array',
        'duty_year' => 'int',
    ];

    protected $with = [
        'ecoSystem',
    ];

    public function ecoSystem(): BelongsTo
    {
        return $this->belongsTo(EcoSystem::class);
    }
}
