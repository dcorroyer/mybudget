<?php

declare(strict_types=1);

namespace App\Shared\Exception;

abstract class AbstractEntityNotFoundException extends AbstractApplicationException
{
    public function __construct(
        string $entityName,
        string $identifier
    ) {
        parent::__construct(\sprintf('%s not found with identifier: %s', $entityName, $identifier), 404);
    }
}
