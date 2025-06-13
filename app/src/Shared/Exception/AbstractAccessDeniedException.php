<?php

declare(strict_types=1);

namespace App\Shared\Exception;

class AbstractAccessDeniedException extends AbstractApplicationException
{
    public function __construct(
        string $resource = 'resource'
    ) {
        parent::__construct(\sprintf('Access denied to %s', $resource), 403);
    }
}
