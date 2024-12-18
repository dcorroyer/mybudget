<?php

declare(strict_types=1);

namespace App\Dto\Account\Response;

use My\RestBundle\Contract\ResponseInterface;

class AccountPartialResponse implements ResponseInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
