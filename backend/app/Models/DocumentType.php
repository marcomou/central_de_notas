<?php

namespace App\Models;

class DocumentType extends Model
{
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'code',
        'description',
        'fields',
    ];

    protected $fillable = [
        'name',
        // 'code',
        'description',
    ];

    protected $casts = [
        'fields' => 'array'
    ];
}
