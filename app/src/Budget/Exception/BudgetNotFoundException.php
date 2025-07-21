<?php

declare(strict_types=1);

namespace App\Budget\Exception;

use App\Shared\Exception\AbstractEntityNotFoundException;

class BudgetNotFoundException extends AbstractEntityNotFoundException
{
    public function __construct(
        int|string $identifier
    ) {
        parent::__construct('Budget', $identifier);
    }
}
