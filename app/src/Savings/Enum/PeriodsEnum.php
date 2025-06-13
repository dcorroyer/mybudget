<?php

declare(strict_types=1);

namespace App\Savings\Enum;

enum PeriodsEnum: string
{
    case SIX_MONTHS = '6';
    case TWELVE_MONTHS = '12';
    case TWO_YEARS = '24';
}
