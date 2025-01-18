<?php

declare(strict_types=1);

namespace App\Budget\Dto\Response;

class IncomeResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $amount,
    ) {
    }
}
