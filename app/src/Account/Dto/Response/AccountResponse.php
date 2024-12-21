<?php

declare(strict_types=1);

namespace App\Account\Dto\Response;

use My\RestBundle\Contract\ResponseInterface;

class AccountResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly float $balance,
    ) {
    }
}
