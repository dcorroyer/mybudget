<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Budget;
use App\Repository\BudgetRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Budget>
 *
 * @method        Budget|Proxy                     create(array|callable $attributes = [])
 * @method static Budget|Proxy                     createOne(array $attributes = [])
 * @method static Budget|Proxy                     find(object|array|mixed $criteria)
 * @method static Budget|Proxy                     findOrCreate(array $attributes)
 * @method static Budget|Proxy                     first(string $sortedField = 'id')
 * @method static Budget|Proxy                     last(string $sortedField = 'id')
 * @method static Budget|Proxy                     random(array $attributes = [])
 * @method static Budget|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BudgetRepository|RepositoryProxy repository()
 * @method static Budget[]|Proxy[]                 all()
 * @method static Budget[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Budget[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Budget[]|Proxy[]                 findBy(array $attributes)
 * @method static Budget[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Budget[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class BudgetFactory extends ModelFactory
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
            'date' => self::faker()->dateTime(),
            'name' => self::faker()->text(255),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
        // ->afterInstantiate(function(Budget $budget): void {})
    }

    protected static function getClass(): string
    {
        return Budget::class;
    }
}
