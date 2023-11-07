<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceStatusReason extends Enum
{
    const NONE_INVOICE_ITEMS_VALID = 'Nenhum dos itens da NF é válida.';
    const NOT_ACCEPTED_INVOICE_OUTPUT = 'Não é aceito notas fiscais de saída.';
}
