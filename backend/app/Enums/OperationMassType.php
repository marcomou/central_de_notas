<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static INPUTED_WEIGHT()
 * @method static static VALIDATED_WEIGHT()
 * @method static static ASSESSED_WEIGHT()
 * @method static static VALIDATED_INCOMING_WEIGHT()
 * @method static static VALIDATED_OUTGOING_WEIGHT()
 * @method static static READ_INCOMING_WEIGHT()
 * @method static static READ_OUTGOING_WEIGHT()
 * @method static static ASSESSED_INPUT_WEIGHT()
 */
final class OperationMassType extends Enum
{
    // TODO reevaluate these types, include more, etc

    const INPUTED_WEIGHT = 'inputed_weight';
    const VALIDATED_WEIGHT = 'validated_weight';
    const ASSESSED_WEIGHT = 'assessed_weight';
    const VALIDATED_INCOMING_WEIGHT = 'validated_incoming_weight';
    const VALIDATED_OUTGOING_WEIGHT = 'validated_outgoing_weight';
    const READ_INCOMING_WEIGHT = 'read_incoming_weight';
    const READ_OUTGOING_WEIGHT = 'read_outgoing_weight';
    const ASSESSED_INPUT_WEIGHT = 'assessed_input_weight';
}
