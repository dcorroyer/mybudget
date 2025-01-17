<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Shared\Repository\UserRepository;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 */
#[Group('integration')]
#[Group('repository')]
#[Group('user')]
#[Group('user-repository')]
final class UserRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private UserRepository $userRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
    }

    #[TestDox('When you send an user and a password into upgradePassword method, it should returns the updated user')]
    #[Test]
    public function upgradePassword_WhenDataOk_ReturnsUpdatedUser(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();

        // ACT
        $this->userRepository->upgradePassword($user, 'password');

        // ASSERT
        self::assertSame('password', $user->getPassword());
    }
}
