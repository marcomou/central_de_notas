<?php

namespace App\Models\Passport;

use Laravel\Passport\RefreshToken as PassportRefreshToken;
use OwenIt\Auditing\Auditable as AuditingTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContracts;

class RefreshToken extends PassportRefreshToken implements AuditableContracts
{
    use AuditingTrait;
}
