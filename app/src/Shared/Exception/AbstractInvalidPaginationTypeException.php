<?php

declare(strict_types=1);

namespace App\Shared\Exception;

class AbstractInvalidPaginationTypeException extends AbstractApplicationException
{
    public function __construct(
        string $providedType
    ) {
        parent::__construct(\sprintf('Invalid pagination type: %s', $providedType), 400);
    }
}
