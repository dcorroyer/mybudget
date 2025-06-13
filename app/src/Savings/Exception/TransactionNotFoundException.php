<?php

declare(strict_types=1);

namespace App\Savings\Exception;

use App\Shared\Exception\AbstractEntityNotFoundException;

class TransactionNotFoundException extends AbstractEntityNotFoundException
{
    public function __construct(
        string $identifier
    ) {
        parent::__construct('Transaction', $identifier);
    }
}
