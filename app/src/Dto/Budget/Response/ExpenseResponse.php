<?php

declare(strict_types=1);

namespace App\Dto\Budget\Response;

use My\RestBundle\Contract\ResponseInterface;

class ExpenseResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $amount,
        public readonly string $category,
    ) {
    }
}
