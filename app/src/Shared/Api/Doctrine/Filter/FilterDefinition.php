<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter;

use App\Shared\Api\Doctrine\Filter\Operator\OperatorInterface;

class FilterDefinition
{
    /**
     * @param array<class-string<OperatorInterface>> $operators
     */
    private function __construct(
        public string $field,
        public string $publicName,
        public array $operators = [],
        public ?FilterJoin $join = null,
    ) {
    }

    /**
     * @param array<class-string<OperatorInterface>> $operators
     */
    public static function create(
        string $field,
        string $publicName,
        array $operators = [],
        ?FilterJoin $join = null,
    ): self {
        return new self(field: $field, publicName: $publicName, operators: $operators, join: $join);
    }
}
