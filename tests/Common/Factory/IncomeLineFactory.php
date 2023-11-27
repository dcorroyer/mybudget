<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\IncomeLine;
use App\Enum\IncomeTypes;
use App\Repository\IncomeLineRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<IncomeLine>
 *
 * @method        IncomeLine|Proxy                     create(array|callable $attributes = [])
 * @method static IncomeLine|Proxy                     createOne(array $attributes = [])
 * @method static IncomeLine|Proxy                     find(object|array|mixed $criteria)
 * @method static IncomeLine|Proxy                     findOrCreate(array $attributes)
 * @method static IncomeLine|Proxy                     first(string $sortedField = 'id')
 * @method static IncomeLine|Proxy                     last(string $sortedField = 'id')
 * @method static IncomeLine|Proxy                     random(array $attributes = [])
 * @method static IncomeLine|Proxy                     randomOrCreate(array $attributes = [])
 * @method static IncomeLineRepository|RepositoryProxy repository()
 * @method static IncomeLine[]|Proxy[]                 all()
 * @method static IncomeLine[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static IncomeLine[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static IncomeLine[]|Proxy[]                 findBy(array $attributes)
 * @method static IncomeLine[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static IncomeLine[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class IncomeLineFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
            'type' => self::faker()->randomElement(IncomeTypes::cases()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(IncomeLine $incomeLine): void {})
        ;
    }

    protected static function getClass(): string
    {
        return IncomeLine::class;
    }
}
