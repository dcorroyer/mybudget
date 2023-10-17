<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\ChargeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChargeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ChargeFactory::new()->createMany(10);
    }
}
