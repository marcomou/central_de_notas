<?php

namespace App\Models;

use App\Enums\ContactType;

class Contact extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'email',
        'document',
        'role',
        'phone',
    ];

    protected $fillable = [
        'eco_membership_id',
        'name',
        'document',
        'email',
        'role',
        'phone',
    ];

    protected $casts = [
        'role' => ContactType::class,
    ];

    public function ecoMembership()
    {
        return $this->belongsTo(EcoMembership::class);
    }
}
