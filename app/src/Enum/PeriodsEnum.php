<?php

declare(strict_types=1);

namespace App\Enum;

enum PeriodsEnum: string
{
    case THREE_MONTHS = '3';
    case SIX_MONTHS = '6';
    case TWELVE_MONTHS = '12';

    case ALL = 'all';
}
