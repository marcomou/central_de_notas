<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class InvoiceFileStatusReason extends Enum
{
    const FILE_CONTENT_IS_NOT_NFE = 'O conteúdo do arquivo não é um XML de NFe válido.';
    const FILE_DUPLICATED = 'O arquivo é duplicado.';
}
