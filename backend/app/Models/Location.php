<?php

namespace App\Models;

class Location extends Model
{
    protected $fillable = [
        'code',
        'acronym',
        'name',
        'region'
    ];
}
