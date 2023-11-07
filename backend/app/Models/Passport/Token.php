<?php

namespace App\Models\Passport;

use Laravel\Passport\Token as PassportToken;
use OwenIt\Auditing\Auditable as AuditingTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContracts;

class Token extends PassportToken implements AuditableContracts
{
    use AuditingTrait;
}
