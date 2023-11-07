<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INTERMEDIATE()
 * @method static static LIABLE()
 * @method static static OPERATOR()
 * @method static static RECYCLER()
 */
final class EcoMembershipRole extends Enum
{
    const INTERMEDIATE = 'intermediate';
    const LIABLE = 'liable';
    const OPERATOR = 'operator';
    const RECYCLER = 'recycler';
}
