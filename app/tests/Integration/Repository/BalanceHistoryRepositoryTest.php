<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Savings\Repository\BalanceHistoryRepository;
use App\Shared\Enum\PeriodsEnum;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\BalanceHistoryFactory;
use App\Tests\Common\Factory\UserFactory;
use Carbon\Carbon;
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
#[Group('balance-history')]
#[Group('balance-history-repository')]
final class BalanceHistoryRepositoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    private BalanceHistoryRepository $balanceHistoryRepository;

    #[\Override]
    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();
        $this->balanceHistoryRepository = $container->get(BalanceHistoryRepository::class);
    }

    #[TestDox('When you find the last balance from history for an account, it should returns last balance')]
    #[Test]
    public function findLatestBalance_WhenDataOk_ReturnsLastBalance(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 100.0,
            'date' => new \DateTime('2024-01-01'),
        ]);

        $lastBalance = BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 200.0,
            'date' => new \DateTime('2024-01-02'),
        ]);

        // ACT
        $latestBalance = $this->balanceHistoryRepository->findLatestBalance($account);

        // ASSERT
        self::assertSame($lastBalance->getBalanceAfterTransaction(), $latestBalance);
    }

    #[TestDox('When you find entries from a date for an account, it should return balance history entries')]
    #[Test]
    public function findEntriesFromDate_WhenDataOk_ReturnsBalanceHistories(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-01');

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2023-12-31'),
        ]);

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-02'),
        ]);

        // ACT
        $entries = $this->balanceHistoryRepository->findEntriesFromDate($account, $date);

        // ASSERT
        self::assertCount(2, $entries);
    }

    #[TestDox(
        'When you find balance before a date for an account, it should return the last balance before that date'
    )]
    #[Test]
    public function findBalanceBeforeDate_WhenDataOk_ReturnsBalance(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime('2024-01-02');

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 100.0,
            'date' => new \DateTime('2024-01-01'),
        ]);

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 200.0,
            'date' => new \DateTime('2024-01-02'),
        ]);

        // ACT
        $balance = $this->balanceHistoryRepository->findBalanceBeforeDate($account, $date);

        // ASSERT
        self::assertSame(100.0, $balance);
    }

    #[TestDox('When you find balances by accounts with period filter, it should return filtered balance histories')]
    #[Test]
    public function findBalancesByAccountsAndByPeriods_WhenDataOk_ReturnsBalanceHistories(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => Carbon::now()->subMonths(7),
        ]);

        $recentHistory = BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => Carbon::now()->subMonths(2),
        ]);

        // ACT
        $histories = $this->balanceHistoryRepository->findBalancesByAccountsAndByPeriods(
            [$account->getId()],
            PeriodsEnum::SIX_MONTHS
        );

        // ASSERT
        self::assertCount(1, $histories);
        self::assertSame($recentHistory->getId(), $histories[0]->getId());
    }

    #[TestDox(
        'When you find balance at end of month for an account, it should return the last balance of that month'
    )]
    #[Test]
    public function findBalanceAtEndOfMonth_WhenDataOk_ReturnsBalance(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 100.0,
            'date' => new \DateTime('2024-01-15'),
        ]);

        $lastBalance = BalanceHistoryFactory::createOne([
            'account' => $account,
            'balanceAfterTransaction' => 200.0,
            'date' => new \DateTime('2024-01-31 23:59:59'),
        ]);

        // ACT
        $balance = $this->balanceHistoryRepository->findBalanceAtEndOfMonth($account, '2024-01');

        // ASSERT
        self::assertSame($lastBalance->getBalanceAfterTransaction(), $balance);
    }

    #[TestDox(
        'When you find balances by multiple accounts with period filter, it should return all filtered balance histories'
    )]
    #[Test]
    public function findBalancesByAccountsAndByPeriods_WithMultipleAccounts_ReturnsAllBalanceHistories(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account1 = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        $account2 = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        // Historiques trop anciens (plus de 6 mois)
        BalanceHistoryFactory::createOne([
            'account' => $account1,
            'date' => Carbon::now()->subMonths(7),
        ]);

        BalanceHistoryFactory::createOne([
            'account' => $account2,
            'date' => Carbon::now()->subMonths(8),
        ]);

        // Historiques récents (moins de 6 mois)
        $recentHistory1 = BalanceHistoryFactory::createOne([
            'account' => $account1,
            'date' => Carbon::now()->subMonths(2),
        ]);

        $recentHistory2 = BalanceHistoryFactory::createOne([
            'account' => $account2,
            'date' => Carbon::now()->subMonths(3),
        ]);

        // ACT
        $histories = $this->balanceHistoryRepository->findBalancesByAccountsAndByPeriods(
            [$account1->getId(), $account2->getId()],
            PeriodsEnum::SIX_MONTHS
        );

        // ASSERT
        self::assertCount(2, $histories);
        $historyIds = array_map(static fn ($history) => $history->getId(), $histories);
        self::assertContains($recentHistory1->_real()->getId(), $historyIds);
        self::assertContains($recentHistory2->_real()->getId(), $historyIds);
    }

    #[TestDox('When finding latest balance for an account with no history, it should return null')]
    #[Test]
    public function findLatestBalance_WithNoHistory_ReturnsNull(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();

        // ACT
        $balance = $this->balanceHistoryRepository->findLatestBalance($account);

        // ASSERT
        self::assertNull($balance);
    }

    #[TestDox('When finding balances by accounts with empty account ids array, it should return empty array')]
    #[Test]
    public function findBalancesByAccountsAndByPeriods_WithEmptyAccountIds_ReturnsEmptyArray(): void
    {
        // ARRANGE
        $emptyAccountIds = [];

        // ACT
        $histories = $this->balanceHistoryRepository->findBalancesByAccountsAndByPeriods($emptyAccountIds);

        // ASSERT
        self::assertEmpty($histories);
    }

    #[TestDox('When finding balance before date with no history, it should return null')]
    #[Test]
    public function findBalanceBeforeDate_WithNoHistory_ReturnsNull(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $date = new \DateTime();

        // ACT
        $balance = $this->balanceHistoryRepository->findBalanceBeforeDate($account, $date);

        // ASSERT
        self::assertNull($balance);
    }

    #[TestDox('When finding entries from date with future date, it should return empty array')]
    #[Test]
    public function findEntriesFromDate_WithFutureDate_ReturnsEmptyArray(): void
    {
        // ARRANGE
        $user = UserFactory::createOne()->_real();
        $account = AccountFactory::createOne([
            'user' => $user,
        ])->_real();
        $futureDate = new \DateTime('+1 year');

        BalanceHistoryFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('now'),
        ]);

        // ACT
        $entries = $this->balanceHistoryRepository->findEntriesFromDate($account, $futureDate);

        // ASSERT
        self::assertEmpty($entries);
    }
}
