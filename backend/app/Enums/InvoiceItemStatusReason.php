<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceItemStatusReason extends Enum
{
    const UNIT_IS_INVALID = 'A unidade de medida não é kilo ou tonelada.';
    const NCM_IS_INVALID = 'O NCM não é compatível com nenhum tipo de material certificado.';
}
