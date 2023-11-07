<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceFileStatus extends Enum
{
    const PENDING = 0;
    const INVALID = 1;
    const DUPLICATED = 2;
    const VALID = 3;
    const COLLIDENCE = 4;
}
