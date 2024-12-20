<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter;

/**
 * @template TKey of string
 * @template TValue of FilterDefinition
 *
 * @extends \ArrayObject<TKey, TValue>
 */
class FilterDefinitionBag extends \ArrayObject
{
    /**
     * @param array<TKey, TValue> $definitions
     */
    public function __construct(
        array $definitions = [],
    ) {
        parent::__construct($definitions);
    }

    /**
     * @param TValue $definition
     */
    public function add(FilterDefinition $definition): self
    {
        $this->offsetSet($definition->field, $definition);

        return $this;
    }

    /**
     * @param TKey $key
     */
    public function get(string $key): FilterDefinition
    {
        return $this->offsetGet($key);
    }

    /**
     * @param TKey $key
     */
    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @param TKey $key
     */
    public function remove(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
