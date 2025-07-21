<?php

declare(strict_types=1);

namespace App\Savings\Enum;

enum TransactionTypesEnum: string
{
    case WITHDRAWAL = 'WITHDRAWAL';
    case DEPOSIT = 'DEPOSIT';
}
