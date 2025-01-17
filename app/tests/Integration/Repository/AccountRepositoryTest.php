<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Savings\Repository\AccountRepository;
use App\Tests\Common\Factory\AccountFactory;
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
#[Group('account')]
#[Group('account-repository')]
final class AccountRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private AccountRepository $accountRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->accountRepository = $container->get(AccountRepository::class);
    }

    #[TestDox('When you send an account and a user into findBy method, it should returns the users account list')]
    #[Test]
    public function findBy_WhenDataOk_ReturnsAccountList(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();

        AccountFactory::createMany(3);
        $accounts = AccountFactory::createMany(3, [
            'user' => $user,
        ]);

        // ACT
        $accountResponse = $this->accountRepository->findBy([
            'user' => $user,
        ]);

        // ASSERT
        self::assertCount(\count($accounts), $accountResponse);
    }
}
