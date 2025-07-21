<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Savings\Entity\MonthlyBalance;
use App\Savings\Entity\Transaction;
use App\Savings\Enum\TransactionTypesEnum;
use App\Savings\Repository\MonthlyBalanceRepository;
use App\Savings\Repository\TransactionRepository;
use App\Savings\Service\AccountService;
use App\Savings\Service\MonthlyBalanceService;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
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
#[Group('monthly-balance')]
final class MonthlyBalanceServiceTest extends TestCase
{
    use Factories;

    private MonthlyBalanceRepository $monthlyBalanceRepository;
    private TransactionRepository $transactionRepository;
    private AccountService $accountService;
    private MonthlyBalanceService $monthlyBalanceService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->monthlyBalanceRepository = $this->createMock(MonthlyBalanceRepository::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->accountService = $this->createMock(AccountService::class);

        $this->monthlyBalanceService = new MonthlyBalanceService(
            monthlyBalanceRepository: $this->monthlyBalanceRepository,
            transactionRepository: $this->transactionRepository,
            accountService: $this->accountService,
        );
    }

    #[TestDox('When updating monthly balance with no transactions, it should create zero balance')]
    #[Test]
    public function updateMonthlyBalance_WithNoTransactions_CreatesZeroBalance(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);
        $month = new \DateTimeImmutable('2024-01-15'); // Mid-month date

        $this->transactionRepository
            ->expects($this->once())
            ->method('findByAccountAndDateRange')
            ->willReturn([])
        ;

        $this->monthlyBalanceRepository
            ->expects($this->exactly(2)) // Called for previous month + current month
            ->method('findByAccountAndMonth')
            ->willReturn(null)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(static function (MonthlyBalance $monthlyBalance) {
                return $monthlyBalance->getEndOfMonthBalance() === 0.0
                    && $monthlyBalance->getTransactionCount() === 0
                    && $monthlyBalance->getMonth()->format('Y-m-d') === '2024-01-01';
            }))
        ;

        // ACT
        $this->monthlyBalanceService->updateMonthlyBalance($account->_real(), $month);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When updating monthly balance with transactions, it should calculate correct balance')]
    #[Test]
    public function updateMonthlyBalance_WithTransactions_CalculatesCorrectBalance(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);
        $month = new \DateTimeImmutable('2024-01-15');

        $transactions = [
            $this->createTransaction(100.0, TransactionTypesEnum::DEPOSIT),
            $this->createTransaction(50.0, TransactionTypesEnum::WITHDRAWAL),
            $this->createTransaction(200.0, TransactionTypesEnum::DEPOSIT),
        ];

        $this->transactionRepository
            ->expects($this->once())
            ->method('findByAccountAndDateRange')
            ->willReturn($transactions)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->exactly(2)) // Called for previous month + current month
            ->method('findByAccountAndMonth')
            ->willReturn(null)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(static function (MonthlyBalance $monthlyBalance) {
                // Expected: 0 + 100 - 50 + 200 = 250
                return $monthlyBalance->getEndOfMonthBalance() === 250.0
                    && $monthlyBalance->getTransactionCount() === 3;
            }))
        ;

        // ACT
        $this->monthlyBalanceService->updateMonthlyBalance($account->_real(), $month);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When handling transaction created, it should update monthly balance')]
    #[Test]
    public function handleTransactionCreated_UpdatesMonthlyBalance(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTimeImmutable('2024-01-15'),
            'amount' => 100.0,
            'type' => TransactionTypesEnum::DEPOSIT,
        ]);

        $this->transactionRepository
            ->expects($this->once())
            ->method('findByAccountAndDateRange')
            ->willReturn([$transaction->_real()])
        ;

        $this->monthlyBalanceRepository
            ->expects($this->exactly(2)) // Called for previous month + current month
            ->method('findByAccountAndMonth')
            ->willReturn(null)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->once())
            ->method('save')
        ;

        // ACT
        $this->monthlyBalanceService->handleTransactionCreated($transaction->_real());

        // ASSERT - Verified by expectations
    }

    #[TestDox('When handling transaction updated with date change, it should recalculate from old month')]
    #[Test]
    public function handleTransactionUpdated_WithDateChange_RecalculatesFromOldMonth(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);
        $transaction = TransactionFactory::createOne([
            'account' => $account,
            'date' => new \DateTimeImmutable('2024-02-15'), // New date
        ]);

        $oldDate = new \DateTimeImmutable('2024-01-15'); // Old date

        $this->monthlyBalanceRepository
            ->expects($this->once()) // Only called once for the earliest month (January)
            ->method('deleteFromMonthOnwards')
            ->with($account->_real(), $this->callback(static function (\DateTimeInterface $date) {
                return $date->format('Y-m-d') === '2024-01-01';
            }))
        ;

        $this->transactionRepository
            ->expects($this->atLeastOnce())
            ->method('findByAccountAndDateRange')
            ->willReturn([])
        ;

        $this->monthlyBalanceRepository
            ->expects($this->atLeastOnce())
            ->method('findByAccountAndMonth')
            ->willReturn(null)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->atLeastOnce())
            ->method('save')
        ;

        // ACT
        $this->monthlyBalanceService->handleTransactionUpdated($transaction->_real(), $oldDate);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When recalculating from month, it should process multiple months correctly')]
    #[Test]
    public function recalculateFromMonth_ProcessesMultipleMonths(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne([
            'id' => 1,
        ]);
        $fromMonth = new \DateTimeImmutable('2024-01-01');

        $this->monthlyBalanceRepository
            ->expects($this->once())
            ->method('deleteFromMonthOnwards')
            ->with($account->_real(), $fromMonth)
        ;

        // Mock responses for multiple months
        $this->transactionRepository
            ->expects($this->atLeastOnce())
            ->method('findByAccountAndDateRange')
            ->willReturn([])
        ;

        $this->monthlyBalanceRepository
            ->expects($this->atLeastOnce())
            ->method('findByAccountAndMonth')
            ->willReturn(null)
        ;

        $this->monthlyBalanceRepository
            ->expects($this->atLeastOnce())
            ->method('save')
        ;

        // ACT
        $this->monthlyBalanceService->recalculateFromMonth($account->_real(), $fromMonth);

        // ASSERT - Verified by expectations
    }

    private function createTransaction(float $amount, TransactionTypesEnum $type): Transaction
    {
        $transaction = $this->createMock(Transaction::class);
        $transaction->method('getAmount')->willReturn($amount);
        $transaction->method('getType')->willReturn($type);

        return $transaction;
    }
}
