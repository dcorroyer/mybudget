<?php

declare(strict_types=1);

namespace App\Shared\Enum;

enum ResourceTypesEnum: string
{
    case ACCOUNT = 'account';
    case TRANSACTION = 'transaction';
    case BUDGET = 'budget';
}
