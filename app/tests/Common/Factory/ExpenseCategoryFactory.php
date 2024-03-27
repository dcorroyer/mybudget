<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\ExpenseCategory;
use App\Repository\ExpenseCategoryRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ExpenseCategory>
 *
 * @method        ExpenseCategory|Proxy                     create(array|callable $attributes = [])
 * @method static ExpenseCategory|Proxy                     createOne(array $attributes = [])
 * @method static ExpenseCategory|Proxy                     find(object|array|mixed $criteria)
 * @method static ExpenseCategory|Proxy                     findOrCreate(array $attributes)
 * @method static ExpenseCategory|Proxy                     first(string $sortedField = 'id')
 * @method static ExpenseCategory|Proxy                     last(string $sortedField = 'id')
 * @method static ExpenseCategory|Proxy                     random(array $attributes = [])
 * @method static ExpenseCategory|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ExpenseCategoryRepository|RepositoryProxy repository()
 * @method static ExpenseCategory[]|Proxy[]                 all()
 * @method static ExpenseCategory[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ExpenseCategory[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ExpenseCategory[]|Proxy[]                 findBy(array $attributes)
 * @method static ExpenseCategory[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ExpenseCategory[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ExpenseCategoryFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
        // ->afterInstantiate(function(ExpenseCategory $expenseCategory): void {})
    }

    protected static function getClass(): string
    {
        return ExpenseCategory::class;
    }
}
