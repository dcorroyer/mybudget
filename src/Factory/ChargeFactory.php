<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Charge;
use App\Repository\ChargeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Charge>
 *
 * @method        Charge|Proxy                     create(array|callable $attributes = [])
 * @method static Charge|Proxy                     createOne(array $attributes = [])
 * @method static Charge|Proxy                     find(object|array|mixed $criteria)
 * @method static Charge|Proxy                     findOrCreate(array $attributes)
 * @method static Charge|Proxy                     first(string $sortedField = 'id')
 * @method static Charge|Proxy                     last(string $sortedField = 'id')
 * @method static Charge|Proxy                     random(array $attributes = [])
 * @method static Charge|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ChargeRepository|RepositoryProxy repository()
 * @method static Charge[]|Proxy[]                 all()
 * @method static Charge[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Charge[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Charge[]|Proxy[]                 findBy(array $attributes)
 * @method static Charge[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Charge[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ChargeFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'date' => self::faker()->dateTime(),
            'chargeLines' => ChargeLineFactory::new()->many(1, 5)->create(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Charge $charge): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Charge::class;
    }
}
