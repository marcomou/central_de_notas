<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static APPROVED()
 * @method static static REPROVED()
 * @method static static REVIEWING()
 * @method static static PENDING()
 * @method static static PROCESSING()
 * @method static static WAITING()
 */
final class HomologationDiagnosticStatus extends Enum
{
    const APPROVED = 'approved';
    const REPROVED = 'reproved';
    const REVIEWING = 'reviewing';
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const WAITING = 'waiting';
}
