<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceItemStatus extends Enum
{
    const PENDING = 'pending';
    const VALID = 'valid';
    const INVALID = 'invalid';
}
