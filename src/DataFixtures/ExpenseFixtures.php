<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\ExpenseFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExpenseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ExpenseFactory::new()->createMany(10);
    }
}
