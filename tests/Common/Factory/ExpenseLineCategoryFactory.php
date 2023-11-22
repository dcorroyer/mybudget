<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\ExpenseLineCategory;
use App\Repository\ExpenseLineCategoryRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ExpenseLineCategory>
 *
 * @method        ExpenseLineCategory|Proxy                     create(array|callable $attributes = [])
 * @method static ExpenseLineCategory|Proxy                     createOne(array $attributes = [])
 * @method static ExpenseLineCategory|Proxy                     find(object|array|mixed $criteria)
 * @method static ExpenseLineCategory|Proxy                     findOrCreate(array $attributes)
 * @method static ExpenseLineCategory|Proxy                     first(string $sortedField = 'id')
 * @method static ExpenseLineCategory|Proxy                     last(string $sortedField = 'id')
 * @method static ExpenseLineCategory|Proxy                     random(array $attributes = [])
 * @method static ExpenseLineCategory|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ExpenseLineCategoryRepository|RepositoryProxy repository()
 * @method static ExpenseLineCategory[]|Proxy[]                 all()
 * @method static ExpenseLineCategory[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ExpenseLineCategory[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ExpenseLineCategory[]|Proxy[]                 findBy(array $attributes)
 * @method static ExpenseLineCategory[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ExpenseLineCategory[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ExpenseLineCategoryFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            'id' => self::faker()->randomDigit(),
            'name' => self::faker()->text(25),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ExpenseLineCategory $category): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ExpenseLineCategory::class;
    }
}
