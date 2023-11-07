<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INTERLOCUTOR()
 * @method static static REPRESENTANTE_LEGAL()
 */
final class ContactType extends Enum
{
    const INTERLOCUTOR = 'interlocutor';
    const REPRESENTANTE_LEGAL = 'representante_legal';
}
