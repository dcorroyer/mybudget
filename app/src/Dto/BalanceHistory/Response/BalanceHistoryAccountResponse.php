<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Response;

class BalanceHistoryAccountResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
