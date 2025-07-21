<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Savings\Entity\MonthlyBalance;
use App\Savings\Enum\PeriodsEnum;
use App\Savings\Repository\MonthlyBalanceRepository;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Functional\TestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('integration')]
#[Group('repository')]
#[Group('monthly-balance')]
#[Group('monthly-balance-repository')]
final class MonthlyBalanceRepositoryTest extends TestBase
{
    use Factories;

    private MonthlyBalanceRepository $monthlyBalanceRepository;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->monthlyBalanceRepository = $this->getContainer()->get(MonthlyBalanceRepository::class);
    }

    #[TestDox('When calling findByAccountsAndPeriod with SIX_MONTHS period, it should return filtered results')]
    #[Test]
    public function findByAccountsAndPeriod_WithSixMonthsPeriod_ReturnsFilteredResults(): void
    {
        // ARRANGE
        $account1 = AccountFactory::createOne();
        $account2 = AccountFactory::createOne();

        // Create monthly balances - some old, some recent
        $oldBalance = new MonthlyBalance();
        $oldBalance->setAccount($account1->_real())
            ->setMonth(new \DateTimeImmutable('-8 months first day of this month'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($oldBalance, true);

        $recentBalance = new MonthlyBalance();
        $recentBalance->setAccount($account1->_real())
            ->setMonth(new \DateTimeImmutable('-3 months first day of this month'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($recentBalance, true);

        $account2Balance = new MonthlyBalance();
        $account2Balance->setAccount($account2->_real())
            ->setMonth(new \DateTimeImmutable('-2 months first day of this month'))
            ->setEndOfMonthBalance(2000.0)
        ;
        $this->monthlyBalanceRepository->save($account2Balance, true);

        $accountIds = [$account1->getId(), $account2->getId()];

        // ACT
        $results = $this->monthlyBalanceRepository->findByAccountsAndPeriod($accountIds, PeriodsEnum::SIX_MONTHS);

        // ASSERT
        // Should only return balances from the last 6 months (exclude the 8-month old balance)
        self::assertCount(2, $results);
        self::assertContains($recentBalance, $results);
        self::assertContains($account2Balance, $results);
        self::assertNotContains($oldBalance, $results);
    }

    #[TestDox('When calling findByAccountsAndPeriod with TWELVE_MONTHS period, it should return filtered results')]
    #[Test]
    public function findByAccountsAndPeriod_WithTwelveMonthsPeriod_ReturnsFilteredResults(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        $oldBalance = new MonthlyBalance();
        $oldBalance->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('-15 months first day of this month'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($oldBalance, true);

        $recentBalance = new MonthlyBalance();
        $recentBalance->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('-6 months first day of this month'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($recentBalance, true);

        $accountIds = [$account->getId()];

        // ACT
        $results = $this->monthlyBalanceRepository->findByAccountsAndPeriod($accountIds, PeriodsEnum::TWELVE_MONTHS);

        // ASSERT
        // Should only return balances from the last 12 months (exclude the 15-month old balance)
        self::assertCount(1, $results);
        self::assertContains($recentBalance, $results);
        self::assertNotContains($oldBalance, $results);
    }

    #[TestDox('When calling findByAccountsAndPeriod without period, it should return all results')]
    #[Test]
    public function findByAccountsAndPeriod_WithoutPeriod_ReturnsAllResults(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        $oldBalance = new MonthlyBalance();
        $oldBalance->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('-24 months first day of this month'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($oldBalance, true);

        $recentBalance = new MonthlyBalance();
        $recentBalance->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('-3 months first day of this month'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($recentBalance, true);

        $accountIds = [$account->getId()];

        // ACT
        $results = $this->monthlyBalanceRepository->findByAccountsAndPeriod($accountIds, null);

        // ASSERT
        // Should return all balances without date filtering
        self::assertCount(2, $results);
        self::assertContains($oldBalance, $results);
        self::assertContains($recentBalance, $results);
    }

    #[TestDox('When calling deleteFromMonthOnwards, it should delete multiple balances from specified month')]
    #[Test]
    public function deleteFromMonthOnwards_WithMultipleBalances_DeletesFromSpecifiedMonth(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        // Create balances for different months
        $balance1 = new MonthlyBalance();
        $balance1->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-01-01'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($balance1, true);

        $balance2 = new MonthlyBalance();
        $balance2->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-03-01'))
            ->setEndOfMonthBalance(1200.0)
        ;
        $this->monthlyBalanceRepository->save($balance2, true);

        $balance3 = new MonthlyBalance();
        $balance3->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-05-01'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($balance3, true);

        // ACT - Delete from March onwards
        $deletedCount = $this->monthlyBalanceRepository->deleteFromMonthOnwards(
            $account->_real(),
            new \DateTimeImmutable('2023-03-01')
        );

        // ASSERT
        self::assertSame(2, $deletedCount); // Should delete March and May balances

        // Verify only January balance remains
        $remainingBalances = $this->monthlyBalanceRepository->findBy([
            'account' => $account->_real(),
        ]);
        self::assertCount(1, $remainingBalances);
        self::assertSame($balance1->getMonth()->format('Y-m'), $remainingBalances[0]->getMonth()->format('Y-m'));
    }

    #[TestDox('When calling countByAccount, it should return correct count of balances')]
    #[Test]
    public function countByAccount_WithMultipleBalances_ReturnsCorrectCount(): void
    {
        // ARRANGE
        $account1 = AccountFactory::createOne();
        $account2 = AccountFactory::createOne();

        // Create 3 balances for account1
        for ($i = 0; $i < 3; ++$i) {
            $balance = new MonthlyBalance();
            $balance->setAccount($account1->_real())
                ->setMonth(new \DateTimeImmutable("-{$i} months first day of this month"))
                ->setEndOfMonthBalance(1000.0 + $i * 100)
            ;
            $this->monthlyBalanceRepository->save($balance, true);
        }

        // Create 1 balance for account2
        $balance = new MonthlyBalance();
        $balance->setAccount($account2->_real())
            ->setMonth(new \DateTimeImmutable('first day of this month'))
            ->setEndOfMonthBalance(2000.0)
        ;
        $this->monthlyBalanceRepository->save($balance, true);

        // ACT
        $count1 = $this->monthlyBalanceRepository->countByAccount($account1->_real());
        $count2 = $this->monthlyBalanceRepository->countByAccount($account2->_real());

        // ASSERT
        self::assertSame(3, $count1);
        self::assertSame(1, $count2);
    }

    #[TestDox('When calling countByAccount with account having no balances, it should return zero')]
    #[Test]
    public function countByAccount_WithNoBalances_ReturnsZero(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        // ACT
        $count = $this->monthlyBalanceRepository->countByAccount($account->_real());

        // ASSERT
        self::assertSame(0, $count);
    }

    #[TestDox('When calling findByAccountAndMonth, it should find balance for exact month')]
    #[Test]
    public function findByAccountAndMonth_WithExactMonth_ReturnsBalance(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $targetMonth = new \DateTimeImmutable('2023-03-15'); // Mid-month date

        $balance = new MonthlyBalance();
        $balance->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-03-01')) // First day of month
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($balance, true);

        // ACT
        $result = $this->monthlyBalanceRepository->findByAccountAndMonth($account->_real(), $targetMonth);

        // ASSERT
        self::assertNotNull($result);
        self::assertSame($balance->getId(), $result->getId());
        self::assertSame('2023-03-01', $result->getMonth()->format('Y-m-d'));
    }

    #[TestDox('When calling findLatestBeforeMonth, it should return the most recent balance before target month')]
    #[Test]
    public function findLatestBeforeMonth_WithMultipleBalances_ReturnsLatestBefore(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        // Create balances for different months
        $balance1 = new MonthlyBalance();
        $balance1->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-01-01'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($balance1, true);

        $balance2 = new MonthlyBalance();
        $balance2->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-03-01'))
            ->setEndOfMonthBalance(1200.0)
        ;
        $this->monthlyBalanceRepository->save($balance2, true);

        $balance3 = new MonthlyBalance();
        $balance3->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-05-01'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($balance3, true);

        // ACT - Find latest before May
        $result = $this->monthlyBalanceRepository->findLatestBeforeMonth(
            $account->_real(),
            new \DateTimeImmutable('2023-05-01')
        );

        // ASSERT
        self::assertNotNull($result);
        self::assertSame($balance2->getId(), $result->getId()); // Should return March balance
        self::assertSame('2023-03-01', $result->getMonth()->format('Y-m-d'));
    }

    #[TestDox('When calling findFromMonthOnwards, it should return all balances from specified month onwards')]
    #[Test]
    public function findFromMonthOnwards_WithMultipleBalances_ReturnsFromMonthOnwards(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        $balance1 = new MonthlyBalance();
        $balance1->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-01-01'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($balance1, true);

        $balance2 = new MonthlyBalance();
        $balance2->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-03-01'))
            ->setEndOfMonthBalance(1200.0)
        ;
        $this->monthlyBalanceRepository->save($balance2, true);

        $balance3 = new MonthlyBalance();
        $balance3->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-05-01'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($balance3, true);

        // ACT - Find from March onwards
        $results = $this->monthlyBalanceRepository->findFromMonthOnwards(
            $account->_real(),
            new \DateTimeImmutable('2023-03-01')
        );

        // ASSERT
        self::assertCount(2, $results); // Should return March and May balances
        self::assertSame($balance2->getId(), $results[0]->getId()); // First should be March
        self::assertSame($balance3->getId(), $results[1]->getId()); // Second should be May
    }

    #[TestDox('When calling findLatestForAccount, it should return the most recent balance')]
    #[Test]
    public function findLatestForAccount_WithMultipleBalances_ReturnsLatest(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();

        $balance1 = new MonthlyBalance();
        $balance1->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-01-01'))
            ->setEndOfMonthBalance(1000.0)
        ;
        $this->monthlyBalanceRepository->save($balance1, true);

        $balance2 = new MonthlyBalance();
        $balance2->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-05-01'))
            ->setEndOfMonthBalance(1500.0)
        ;
        $this->monthlyBalanceRepository->save($balance2, true);

        $balance3 = new MonthlyBalance();
        $balance3->setAccount($account->_real())
            ->setMonth(new \DateTimeImmutable('2023-03-01'))
            ->setEndOfMonthBalance(1200.0)
        ;
        $this->monthlyBalanceRepository->save($balance3, true);

        // ACT
        $result = $this->monthlyBalanceRepository->findLatestForAccount($account->_real());

        // ASSERT
        self::assertNotNull($result);
        self::assertSame($balance2->getId(), $result->getId()); // Should return May balance (latest)
        self::assertSame('2023-05-01', $result->getMonth()->format('Y-m-d'));
    }
}
