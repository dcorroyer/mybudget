<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Tracking>
 *
 * @method        Tracking|Proxy                     create(array|callable $attributes = [])
 * @method static Tracking|Proxy                     createOne(array $attributes = [])
 * @method static Tracking|Proxy                     find(object|array|mixed $criteria)
 * @method static Tracking|Proxy                     findOrCreate(array $attributes)
 * @method static Tracking|Proxy                     first(string $sortedField = 'id')
 * @method static Tracking|Proxy                     last(string $sortedField = 'id')
 * @method static Tracking|Proxy                     random(array $attributes = [])
 * @method static Tracking|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TrackingRepository|RepositoryProxy repository()
 * @method static Tracking[]|Proxy[]                 all()
 * @method static Tracking[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Tracking[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Tracking[]|Proxy[]                 findBy(array $attributes)
 * @method static Tracking[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Tracking[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TrackingFactory extends ModelFactory
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
            'expense' => ExpenseFactory::new()->withoutPersisting()->create(),
            'income' => IncomeFactory::new()->withoutPersisting()->create(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Tracking $tracking): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tracking::class;
    }
}
