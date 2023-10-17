<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\ChargeLine;
use App\Repository\ChargeLineRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ChargeLine>
 *
 * @method        ChargeLine|Proxy                     create(array|callable $attributes = [])
 * @method static ChargeLine|Proxy                     createOne(array $attributes = [])
 * @method static ChargeLine|Proxy                     find(object|array|mixed $criteria)
 * @method static ChargeLine|Proxy                     findOrCreate(array $attributes)
 * @method static ChargeLine|Proxy                     first(string $sortedField = 'id')
 * @method static ChargeLine|Proxy                     last(string $sortedField = 'id')
 * @method static ChargeLine|Proxy                     random(array $attributes = [])
 * @method static ChargeLine|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ChargeLineRepository|RepositoryProxy repository()
 * @method static ChargeLine[]|Proxy[]                 all()
 * @method static ChargeLine[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ChargeLine[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ChargeLine[]|Proxy[]                 findBy(array $attributes)
 * @method static ChargeLine[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ChargeLine[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ChargeLineFactory extends ModelFactory
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
            'name' => self::faker()->text(25),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ChargeLine $chargeLine): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ChargeLine::class;
    }
}
