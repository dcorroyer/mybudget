<?php

declare(strict_types=1);

namespace My\RestBundle\Enum;

use My\RestBundle\Adapter\ErrorCodesInterface;

enum ErrorCodes: string implements ErrorCodesInterface
{
    case NOT_FOUND = 'NOT_FOUND';

    public function getErrorCode(): string
    {
        return $this->value;
    }
}
