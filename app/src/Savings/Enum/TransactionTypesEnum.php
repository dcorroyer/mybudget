<?php

declare(strict_types=1);

namespace App\Savings\Enum;

enum TransactionTypesEnum: string
{
    case DEBIT = 'DEBIT';
    case CREDIT = 'CREDIT';
}
