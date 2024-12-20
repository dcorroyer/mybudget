<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Func;

final readonly class FilterJoin
{
    public function __construct(
        public string $join,
        public string $alias,
        public ?string $conditionType = null,
        public string|Composite|Comparison|Func|null $condition = null,
        public ?string $indexBy = null,
    ) {
    }

    public static function create(
        string $join,
        string $alias,
        ?string $conditionType = null,
        string|Composite|Comparison|Func|null $condition = null,
        ?string $indexBy = null,
    ): self {
        return new self($join, $alias, $conditionType, $condition, $indexBy);
    }
}
