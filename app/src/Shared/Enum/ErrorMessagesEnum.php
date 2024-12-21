<?php

declare(strict_types=1);

namespace App\Shared\Enum;

enum ErrorMessagesEnum: string
{
    case ACCESS_DENIED = 'Access Denied.';
    case TRANSACTION_NOT_FOUND = 'Transaction not found';
    case ACCOUNT_NOT_FOUND = 'Account not found';
    case BUDGET_NOT_FOUND = 'Budget not found';
}
