<?php

declare(strict_types=1);

namespace App\Savings\Exception;

use App\Shared\Exception\AbstractEntityNotFoundException;

class AccountNotFoundException extends AbstractEntityNotFoundException
{
    public function __construct(
        int $identifier
    ) {
        parent::__construct('Account', $identifier);
    }
}
