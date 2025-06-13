<?php

declare(strict_types=1);

namespace App\Savings\Exception;

use App\Shared\Exception\AbstractEntityNotFoundException;

class AccountNotFoundException extends AbstractEntityNotFoundException
{
    public function __construct(
        string $identifier
    ) {
        parent::__construct('Account', $identifier);
    }
}
