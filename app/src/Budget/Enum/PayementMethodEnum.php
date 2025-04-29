<?php

declare(strict_types=1);

namespace App\Budget\Enum;

enum PayementMethodEnum: string
{
    case BILLS_ACCOUNT = 'BILLS_ACCOUNT';
    case BANK_TRANSFER = 'BANK_TRANSFER';
    case OTHER = 'OTHER';
}
