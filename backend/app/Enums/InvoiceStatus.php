<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceStatus extends Enum
{
    const PENDING = 'registered';
    const VALID = 'validated';
    const INVALID = 'invalid';
    const REJECTED = 'rejected';
}
