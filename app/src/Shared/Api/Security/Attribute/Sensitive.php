<?php

declare(strict_types=1);

namespace App\Shared\Api\Security\Attribute;

/**
 * TODO: tests this when user implementation is ready.
 * Use this attribute to hide/show sensitive data based on the user roles.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Sensitive
{
    /**
     * @param array<string> $roles
     */
    public function __construct(
        public array $roles = [],
    ) {
    }
}
