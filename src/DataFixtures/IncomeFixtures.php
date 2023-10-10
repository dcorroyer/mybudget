<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\IncomeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IncomeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        IncomeFactory::new()->createMany(10);
    }
}
