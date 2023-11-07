<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TREASURER_EXTRACTED()
 * @method static static TREASURER_OFFICIALLY_CHECKED()
 * @method static static DATA_SEEDED()
 * @method static static USER_PROVIDED()
 */
final class AddressSourceTypes extends Enum
{
    const TREASURER_EXTRACTED = 'treasurer.extracted'; // for example, via Infosimples API
    const TREASURER_OFFICIALLY_CHECKED = 'treasurer.official'; // official Receita Federal API
    const DATA_SEEDED = 'data.seeded'; // created via migrations or database initial seeds
    const USER_PROVIDED = 'user.provided'; // typed in by a user
    // TODO maybe: be more specific if user provided (via what form? was it helped by CEP search? etc)
}
