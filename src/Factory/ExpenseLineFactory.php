<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ExpenseLine;
use App\Repository\ExpenseLineRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ExpenseLine>
 *
 * @method        ExpenseLine|Proxy                     create(array|callable $attributes = [])
 * @method static ExpenseLine|Proxy                     createOne(array $attributes = [])
 * @method static ExpenseLine|Proxy                     find(object|array|mixed $criteria)
 * @method static ExpenseLine|Proxy                     findOrCreate(array $attributes)
 * @method static ExpenseLine|Proxy                     first(string $sortedField = 'id')
 * @method static ExpenseLine|Proxy                     last(string $sortedField = 'id')
 * @method static ExpenseLine|Proxy                     random(array $attributes = [])
 * @method static ExpenseLine|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ExpenseLineRepository|RepositoryProxy repository()
 * @method static ExpenseLine[]|Proxy[]                 all()
 * @method static ExpenseLine[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ExpenseLine[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ExpenseLine[]|Proxy[]                 findBy(array $attributes)
 * @method static ExpenseLine[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ExpenseLine[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ExpenseLineFactory extends ModelFactory
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
            'amount' => self::faker()->randomFloat(),
            'name' => self::faker()->text(25),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ExpenseLine $expenseLine): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ExpenseLine::class;
    }
}
