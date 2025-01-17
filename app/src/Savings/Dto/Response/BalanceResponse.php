<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

class BalanceResponse
{
    public function __construct(
        public readonly string $date,
        public readonly string $formattedDate,
        public readonly float $balance,
    ) {
    }
}
