<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use App\Shared\Api\Dto\Contract\ResponseInterface;

class BalanceResponse implements ResponseInterface
{
    public function __construct(
        public readonly string $date,
        public readonly string $formattedDate,
        public readonly float $balance,
    ) {
    }
}
