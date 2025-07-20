<?php

declare(strict_types=1);

namespace App\Savings\Enum;

enum TransactionTypesEnum: string
{
    case WITHDRAWAL = 'WITHDRAWAL';
    case DEPOSIT = 'DEPOSIT';
    
    // Temporary aliases for backward compatibility during migration
    public const DEBIT = 'WITHDRAWAL';
    public const CREDIT = 'DEPOSIT';
}
