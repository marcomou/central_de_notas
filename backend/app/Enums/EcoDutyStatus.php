<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DRAFT()
 * @method static static REPLACED()
 * @method static static SUBMITTED()
 * @method static static MISSDUE()
 * @method static static EDITING()
 * @method static static APPROVED()
 * @method static static VERIFIED()
 */
final class EcoDutyStatus extends Enum
{
    const DRAFT = 'draft';
    const REPLACED = 'replaced';
    const SUBMITTED = 'submitted';
    const MISSDUE = 'missdue';
    const EDITING = 'editing';
    const APPROVED = 'approved';
    const VERIFIED = 'verified';
}
