<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Expense>
 *
 * @method        Expense|Proxy                     create(array|callable $attributes = [])
 * @method static Expense|Proxy                     createOne(array $attributes = [])
 * @method static Expense|Proxy                     find(object|array|mixed $criteria)
 * @method static Expense|Proxy                     findOrCreate(array $attributes)
 * @method static Expense|Proxy                     first(string $sortedField = 'id')
 * @method static Expense|Proxy                     last(string $sortedField = 'id')
 * @method static Expense|Proxy                     random(array $attributes = [])
 * @method static Expense|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ExpenseRepository|RepositoryProxy repository()
 * @method static Expense[]|Proxy[]                 all()
 * @method static Expense[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Expense[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Expense[]|Proxy[]                 findBy(array $attributes)
 * @method static Expense[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Expense[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ExpenseFactory extends ModelFactory
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
            'amount' => self::faker()->randomFloat(),
            'expenseLines' => ExpenseLineFactory::new([
                'expense' => $this,
                'category' => ExpenseCategoryFactory::new()->withoutPersisting()->create(),
            ])->withoutPersisting()
                ->many(2)
                ->create(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
        // ->afterInstantiate(function(Expense $expense): void {})
    }

    protected static function getClass(): string
    {
        return Expense::class;
    }
}
