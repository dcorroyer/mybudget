<?php

declare(strict_types=1);

namespace App\Shared\Api\Mapper;

use AutoMapper\AutoMapper;
use AutoMapper\MapperContext;

/**
 * @phpstan-import-type MapperContextArray from MapperContext
 */
class ApiMapper
{
    public function __construct(
        private readonly AutoMapper $autoMapper,
        //        private readonly RelationResolver $relationResolver,
    ) {
    }

    /**
     * @template Source of object
     * @template Target of object
     *
     * @param Source|array<mixed>                      $source
     * @param class-string<Target>|array<mixed>|Target $target
     *
     * @return ($target is class-string|Target ? Target|null : array<mixed>|null)
     */
    public function map(array|object $source, string|array|object $target): object|array|null
    {
        $target = $this->autoMapper->map($source, $target);
        if ($target !== null) {
            //        $this->relationResolver->resolve($source, $target);
        }

        return $target;
    }

    /**
     * @template Source of object
     * @template Target of object
     *
     * @param Source|array<mixed>                      $source
     * @param class-string<Target>|array<mixed>|Target $target
     *
     * @return ($target is class-string|Target ? Target|null : array<mixed>|null)
     */
    public function patch(array|object $source, string|array|object $target): array|object|null
    {
        if (\is_object($source) === false) {
            return $this->map($source, $target);
        }

        $reflectionClass = new \ReflectionClass($source);
        $data = [];
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->isInitialized($source) === false) {
                continue;
            }

            if (
                $reflectionProperty->getValue($source) === null
                //                && $reflectionProperty->getType()?->getName() === Relation::class
            ) {
                continue;
            }

            $data[$reflectionProperty->getName()] = $reflectionProperty->getValue($source);
        }

        $target = $this->autoMapper->map($data, $target);
        if ($target !== null) {
            //        $this->relationResolver->resolve($source, $target);
        }

        return $target;
    }
}
