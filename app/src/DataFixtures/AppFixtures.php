<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder
    ) {
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $hashedPassword = $this->passwordEncoder->hashPassword($user, 'password');

        $user = UserFactory::new([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@admin.local',
            'password' => $hashedPassword,
        ])->create();

        BudgetFactory::createMany(25, [
            'user' => $user,
        ]);
    }
}
