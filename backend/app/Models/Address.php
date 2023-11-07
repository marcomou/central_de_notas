<?php

namespace App\Models;

use App\Enums\AddressSourceTypes;

class Address extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'organization_id',
        'street',
        'number',
        'additional_info',
        'postal_code',
        'city',
        'state',
        'country',
        'source',
    ];

    protected $fillable = [
        // 'organization_id',
        'street',
        'number',
        'additional_info',
        'postal_code',
        'city',
        'state',
        'country',
        'source',
    ];

    protected $casts = [
        'source' => AddressSourceTypes::class,
    ];

    public function scopeSourcedByTreasury($query)
    {
        $query->whereIn('source', [
            AddressSourceTypes::TREASURER_EXTRACTED,
            AddressSourceTypes::TREASURER_OFFICIALLY_CHECKED,
        ]);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
