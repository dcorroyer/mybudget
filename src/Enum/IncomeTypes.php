<?php

declare(strict_types=1);

namespace App\Enum;

enum IncomeTypes: string
{
    case SALARY = 'Salary';
    case DIVIDENDS = 'Dividends';
}
