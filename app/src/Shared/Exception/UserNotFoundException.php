<?php

declare(strict_types=1);

namespace App\Shared\Exception;

class UserNotFoundException extends AbstractEntityNotFoundException
{
    public function __construct(
        string $identifier
    ) {
        parent::__construct('User', $identifier);
    }
}
