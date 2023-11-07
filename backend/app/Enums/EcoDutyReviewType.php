<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static NOTIFICATIION()
 */
final class EcoDutyReviewType extends Enum
{
    // TODO reevaluate these types, include more, etc

    const PENDING = 'pending';
    const NOTIFICATIION = 'notification';
}
