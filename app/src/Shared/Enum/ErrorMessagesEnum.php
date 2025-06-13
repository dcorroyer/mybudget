<?php

declare(strict_types=1);

namespace App\Shared\Enum;

enum ErrorMessagesEnum: string
{
    case ACCESS_DENIED = 'Access denied to resource';
    case BUDGET_NOT_FOUND = 'Budget not found with identifier: %s';
    case ACCOUNT_NOT_FOUND = 'Account not found with identifier: %s';
    case TRANSACTION_NOT_FOUND = 'Transaction not found with identifier: %s';
}
