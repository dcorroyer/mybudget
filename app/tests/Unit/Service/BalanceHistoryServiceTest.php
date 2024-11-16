<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\BalanceHistory\Response\BalanceHistoryResponse;
use App\Entity\BalanceHistory;
use App\Enum\PeriodsEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\BalanceHistoryRepository;
use App\Repository\TransactionRepository;
use App\Service\AccountService;
use App\Service\BalanceHistoryService;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('service')]
#[Group('balance-history')]
#[Group('balance-history-service')]
final class BalanceHistoryServiceTest extends TestCase
{
    use Factories;

    private BalanceHistoryRepository $balanceHistoryRepository;
    private TransactionRepository $transactionRepository;
    private AccountService $accountService;
    private BalanceHistoryService $balanceHistoryService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->balanceHistoryRepository = $this->createMock(BalanceHistoryRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->accountService = $this->createMock(AccountService::class);

        $this->balanceHistoryService = new BalanceHistoryService(
            balanceHistoryRepository: $this->balanceHistoryRepository,
            transactionRepository: $this->transactionRepository,
            accountService: $this->accountService,
        );
    }

    #[TestDox('When creating balance history entry, it should create entry with correct balances')]
    #[Test]
    public function createBalanceHistoryEntry_WhenDataOk_CreatesEntry(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'amount' => 100.0,
            'type' => TransactionTypesEnum::CREDIT,
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findLatestBalance')
            ->willReturn(50.0)
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(static function (BalanceHistory $history) use ($transaction) {
                    return $history->getBalanceBeforeTransaction() === 50.0
                        && $history->getBalanceAfterTransaction() === 150.0
                        && $history->getTransaction() === $transaction->_real()
                        && $history->getAccount() === $transaction->getAccount();
                }),
                true
            )
        ;

        // ACT
        $this->balanceHistoryService->createBalanceHistoryEntry($transaction->_real());

        // ASSERT
        $this->assertTrue(true);
    }

    #[TestDox('When getting latest balance, it should return the balance from repository')]
    #[Test]
    public function getLatestBalance_WhenDataOk_ReturnsBalance(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findLatestBalance')
            ->willReturn(100.0)
        ;

        // ACT
        $balance = $this->balanceHistoryService->getLatestBalance($account->_real());

        // ASSERT
        self::assertSame(100.0, $balance);
    }

    #[TestDox('When updating balance history entry, it should recalculate balances')]
    #[Test]
    public function updateBalanceHistoryEntry_WhenDataOk_RecalculatesBalances(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findEntriesFromDate')
            ->willReturn([])
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalanceBeforeDate')
            ->willReturn(50.0)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('findAllTransactionsFromDate')
            ->willReturn([$transaction->_real()])
        ;

        // ACT
        $this->balanceHistoryService->updateBalanceHistoryEntry($transaction->_real());

        // ASSERT
        $this->assertTrue(true);
    }

    #[TestDox('When deleting balance history entry, it should recalculate balances excluding the deleted transaction')]
    #[Test]
    public function deleteBalanceHistoryEntry_WhenDataOk_RecalculatesBalances(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findEntriesFromDate')
            ->willReturn([])
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalanceBeforeDate')
            ->willReturn(50.0)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('findAllTransactionsFromDateExcept')
            ->willReturn([])
        ;

        // ACT
        $this->balanceHistoryService->deleteBalanceHistoryEntry($transaction);

        // ASSERT
        $this->assertTrue(true);
    }

    #[TestDox('When getting monthly balance history, it should return history for all accounts')]
    #[Test]
    public function getMonthlyBalanceHistory_WhenDataOk_ReturnsHistory(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account1 = AccountFactory::createOne([
            'user' => $user,
            'name' => 'Account 1',
        ]);
        $account2 = AccountFactory::createOne([
            'user' => $user,
            'name' => 'Account 2',
        ]);

        $balanceHistory = new BalanceHistory();
        $balanceHistory->setDate(new \DateTime('2024-01-15'));

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalancesByAccounts')
            ->with([$account1->getId(), $account2->getId()], PeriodsEnum::SIX_MONTHS)
            ->willReturn([$balanceHistory])
        ;

        $this->balanceHistoryRepository->expects($this->exactly(2))
            ->method('findBalanceAtEndOfMonth')
            ->willReturnOnConsecutiveCalls(100.0, 200.0)
        ;

        // ACT
        $response = $this->balanceHistoryService->getMonthlyBalanceHistory(
            [$account1->getId(), $account2->getId()],
            PeriodsEnum::SIX_MONTHS
        );

        // ASSERT
        self::assertInstanceOf(BalanceHistoryResponse::class, $response);
        self::assertCount(2, $response->accounts);
        self::assertCount(1, $response->balances);
        self::assertSame(300.0, $response->balances[0]->balance);
    }

    #[TestDox('When getting monthly balance history with no accounts specified, it should use all user accounts')]
    #[Test]
    public function getMonthlyBalanceHistory_WithNoAccounts_UsesAllUserAccounts(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        $this->accountService->expects($this->once())
            ->method('list')
            ->willReturn([$account->_real()])
        ;

        $balanceHistory = new BalanceHistory();
        $balanceHistory->setDate(new \DateTime('2024-01-15'));

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalancesByAccounts')
            ->willReturn([$balanceHistory])
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalanceAtEndOfMonth')
            ->willReturn(100.0)
        ;

        // ACT
        $response = $this->balanceHistoryService->getMonthlyBalanceHistory();

        // ASSERT
        self::assertInstanceOf(BalanceHistoryResponse::class, $response);
        self::assertCount(1, $response->accounts);
        self::assertCount(1, $response->balances);
    }

    #[TestDox('When creating balance history entry with debit transaction, it should decrease balance')]
    #[Test]
    public function createBalanceHistoryEntry_WithDebitTransaction_DecreasesBalance(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'amount' => 100.0,
            'type' => TransactionTypesEnum::DEBIT,
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findLatestBalance')
            ->willReturn(150.0)
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(static function (BalanceHistory $history) use ($transaction) {
                    return $history->getBalanceBeforeTransaction() === 150.0
                        && $history->getBalanceAfterTransaction() === 50.0
                        && $history->getTransaction() === $transaction
                        && $history->getAccount() === $transaction->getAccount();
                }),
                true
            )
        ;

        // ACT
        $this->balanceHistoryService->createBalanceHistoryEntry($transaction);
    }

    #[TestDox('When updating balance history entry, it should handle multiple transactions correctly')]
    #[Test]
    public function updateBalanceHistoryEntry_WithMultipleTransactions_RecalculatesBalancesCorrectly(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);
        $transaction1 = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-01'),
            'amount' => 100.0,
            'type' => TransactionTypesEnum::CREDIT,
        ]);
        $transaction2 = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTime('2024-01-02'),
            'amount' => 50.0,
            'type' => TransactionTypesEnum::DEBIT,
        ]);

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findEntriesFromDate')
            ->willReturn([])
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalanceBeforeDate')
            ->willReturn(50.0)
        ;

        $this->transactionRepository->expects($this->once())
            ->method('findAllTransactionsFromDate')
            ->willReturn([$transaction1, $transaction2])
        ;

        $expectedBalances = [[50.0, 150.0], [150.0, 100.0]];
        $currentBalanceIndex = 0;

        $this->balanceHistoryRepository->expects($this->exactly(2))
            ->method('save')
            ->with(
                $this->callback(static function (BalanceHistory $history) use (
                    &$currentBalanceIndex,
                    $expectedBalances
                ) {
                    $result = $history->getBalanceBeforeTransaction() === $expectedBalances[$currentBalanceIndex][0]
                        && $history->getBalanceAfterTransaction() === $expectedBalances[$currentBalanceIndex][1];
                    ++$currentBalanceIndex;

                    return $result;
                })
            )
        ;

        // ACT
        $this->balanceHistoryService->updateBalanceHistoryEntry($transaction1);
    }

    #[TestDox('When getting monthly balance history with no data, it should return empty balances')]
    #[Test]
    public function getMonthlyBalanceHistory_WithNoData_ReturnsEmptyBalances(): void
    {
        // ARRANGE
        $user = UserFactory::createOne();
        $account = AccountFactory::createOne([
            'user' => $user,
        ]);

        $this->accountService->expects($this->once())
            ->method('list')
            ->willReturn([$account->_real()])
        ;

        $this->balanceHistoryRepository->expects($this->once())
            ->method('findBalancesByAccounts')
            ->willReturn([])
        ;

        // ACT
        $response = $this->balanceHistoryService->getMonthlyBalanceHistory();

        // ASSERT
        self::assertInstanceOf(BalanceHistoryResponse::class, $response);
        self::assertCount(1, $response->accounts);
        self::assertEmpty($response->balances);
    }
}
