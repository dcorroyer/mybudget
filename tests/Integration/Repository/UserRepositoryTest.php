<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testUpgradePasswordReturnsUser(): void
    {
        // ARRANGE
        $user = UserFactory::new()->create()->object();
        $newHashedPassword = 'hashedPassword123';

        // ACT
        $this->userRepository->upgradePassword($user, $newHashedPassword);

        // ASSERT
        $this->assertEquals($newHashedPassword, $user->getPassword());
    }
}
