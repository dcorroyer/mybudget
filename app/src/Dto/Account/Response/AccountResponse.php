<?php

declare(strict_types=1);

namespace App\Dto\Account\Response;

class AccountResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly float $balance,
    ) {
    }
}
