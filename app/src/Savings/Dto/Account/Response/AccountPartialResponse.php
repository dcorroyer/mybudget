<?php

declare(strict_types=1);

namespace App\Savings\Dto\Account\Response;

class AccountPartialResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
