<?php

declare(strict_types=1);

namespace App\Dto\Savings\Response;

class AccountPartialResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
