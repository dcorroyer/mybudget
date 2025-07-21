<?php

declare(strict_types=1);

namespace App\Savings\Service;

use App\Savings\Dto\Response\AccountPartialResponse;
use App\Savings\Dto\Response\BalanceHistoryResponse;
use App\Savings\Dto\Response\BalanceResponse;
use App\Savings\Entity\Account;
use App\Savings\Entity\MonthlyBalance;
use App\Savings\Entity\Transaction;
use App\Savings\Enum\PeriodsEnum;
use App\Savings\Enum\TransactionTypesEnum;
use App\Savings\Repository\MonthlyBalanceRepository;
use App\Savings\Repository\TransactionRepository;
use loophp\collection\Collection;

class MonthlyBalanceService
{
    public function __construct(
        private readonly MonthlyBalanceRepository $monthlyBalanceRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountService $accountService,
    ) {
    }

    /**
     * Update or create MonthlyBalance for a specific month.
     */
    public function updateMonthlyBalance(Account $account, \DateTimeInterface $month): void
    {
        $firstDayOfMonth = (new \DateTimeImmutable($month->format('Y-m-01')))->setTime(0, 0, 0);
        $lastDayOfMonth = $firstDayOfMonth->modify('last day of this month')->setTime(23, 59, 59);

        // Get the balance at the end of previous month
        $previousMonthBalance = $this->getBalanceEndOfPreviousMonth($account, $firstDayOfMonth);

        // Get all transactions for this month
        $transactions = $this->transactionRepository->findByAccountAndDateRange(
            $account,
            $firstDayOfMonth,
            $lastDayOfMonth
        );

        // Calculate end of month balance
        $endOfMonthBalance = $this->calculateEndOfMonthBalance($previousMonthBalance, $transactions);

        // Find or create MonthlyBalance
        $monthlyBalance = $this->monthlyBalanceRepository->findByAccountAndMonth($account, $firstDayOfMonth);

        if ($monthlyBalance === null) {
            $monthlyBalance = MonthlyBalance::forMonth($account, $firstDayOfMonth);
        }

        $monthlyBalance->setEndOfMonthBalance($endOfMonthBalance)
            ->setTransactionCount(\count($transactions))
            ->updateLastUpdated()
        ;

        $this->monthlyBalanceRepository->save($monthlyBalance, true);
    }

    /**
     * Recalculate all MonthlyBalance from a specific month onwards.
     */
    public function recalculateFromMonth(Account $account, \DateTimeInterface $fromMonth): void
    {
        $currentMonth = (new \DateTimeImmutable($fromMonth->format('Y-m-01')))->setTime(0, 0, 0);
        $thisMonth = (new \DateTimeImmutable())->modify('first day of this month')->setTime(0, 0, 0);

        // Delete existing MonthlyBalance from this month onwards
        $this->monthlyBalanceRepository->deleteFromMonthOnwards($account, $currentMonth);

        // Recalculate month by month
        while ($currentMonth <= $thisMonth) {
            $this->updateMonthlyBalance($account, $currentMonth);
            $currentMonth = $currentMonth->modify('+1 month');
        }
    }

    /**
     * Handle transaction events for MonthlyBalance updates.
     */
    public function handleTransactionCreated(Transaction $transaction): void
    {
        $account = $transaction->getAccount();
        $transactionMonth = $transaction->getDate();

        $this->updateMonthlyBalance($account, $transactionMonth);
    }

    public function handleTransactionUpdated(Transaction $transaction, ?\DateTimeInterface $oldDate = null): void
    {
        $account = $transaction->getAccount();
        $currentMonth = $transaction->getDate();

        // If date changed, we need to update both months
        if ($oldDate !== null && $oldDate->format('Y-m') !== $currentMonth->format('Y-m')) {
            $oldMonth = new \DateTimeImmutable($oldDate->format('Y-m-01'));
            $this->recalculateFromMonth($account, $oldMonth);
        } else {
            // Same month, just recalculate from this month
            $this->recalculateFromMonth($account, $currentMonth);
        }
    }

    public function handleTransactionDeleted(Transaction $transaction): void
    {
        $account = $transaction->getAccount();
        $transactionMonth = $transaction->getDate();

        $this->recalculateFromMonth($account, $transactionMonth);
    }

    /**
     * Get balance history for charts (replaces old BalanceHistoryService).
     *
     * @param array<int>|null $accountIds
     */
    public function getMonthlyBalanceHistory(
        ?array $accountIds = null,
        ?PeriodsEnum $periodFilter = null
    ): BalanceHistoryResponse {
        $accountsInfo = [];

        if ($accountIds !== null) {
            foreach ($accountIds as $accountId) {
                $account = $this->accountService->get($accountId);
                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
            }
        } else {
            $userAccounts = $this->accountService->list();
            $accountIds = [];

            foreach ($userAccounts as $account) {
                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
                $accountIds[] = $account->getId();
            }
        }

        $monthlyBalances = $this->monthlyBalanceRepository->findByAccountsAndPeriod($accountIds, $periodFilter);

        // Fill missing months with stable balance data
        $completeMonthlyBalances = $this->fillMissingMonths($monthlyBalances, $accountIds, $periodFilter);

        // Extract unique months and sort them
        $months = Collection::fromIterable($completeMonthlyBalances)
            ->map(static fn (MonthlyBalance $balance) => $balance->getMonth()->format('Y-m'))
            ->distinct()
            ->sort()
            ->all()
        ;

        $balanceData = [];

        // For each month, sum balances across all accounts
        foreach ($months as $month) {
            $monthTotal = Collection::fromIterable($completeMonthlyBalances)
                ->filter(static fn (MonthlyBalance $balance) => $balance->getMonth()->format('Y-m') === $month)
                ->map(static fn (MonthlyBalance $balance) => $balance->getEndOfMonthBalance())
                ->reduce(static fn (float $carry, float $balance) => $carry + $balance, 0.0)
            ;

            $date = new \DateTimeImmutable($month . '-01');
            $balanceData[] = new BalanceResponse(
                date: $month,  // Format Y-m comme attendu par les tests
                formattedDate: $date->format('M Y'),
                balance: $monthTotal
            );
        }

        return new BalanceHistoryResponse(accounts: $accountsInfo, balances: $balanceData);
    }

    /**
     * Get balance at the end of previous month.
     */
    private function getBalanceEndOfPreviousMonth(Account $account, \DateTimeInterface $month): float
    {
        $previousMonth = (new \DateTimeImmutable($month->format('Y-m-01')))
            ->modify('-1 month')
            ->setTime(0, 0, 0)
        ;

        $previousMonthBalance = $this->monthlyBalanceRepository->findByAccountAndMonth($account, $previousMonth);

        return $previousMonthBalance?->getEndOfMonthBalance() ?? 0.0;
    }

    /**
     * Calculate end of month balance from previous balance and transactions.
     *
     * @param array<Transaction> $transactions
     */
    private function calculateEndOfMonthBalance(float $previousBalance, array $transactions): float
    {
        $balance = $previousBalance;

        foreach ($transactions as $transaction) {
            $amount = $transaction->getAmount();
            $balance += $transaction->getType() === TransactionTypesEnum::DEPOSIT ? $amount : -$amount;
        }

        return $balance;
    }

    /**
     * Fill missing months with stable balance data for better chart visualization.
     *
     * @param array<MonthlyBalance> $existingBalances
     * @param array<int>            $accountIds
     *
     * @return array<MonthlyBalance>
     */
    private function fillMissingMonths(array $existingBalances, array $accountIds, ?PeriodsEnum $periodFilter): array
    {
        $now = new \DateTimeImmutable();
        $endDate = $now->modify('first day of this month');

        // Determine the date range to cover
        if ($periodFilter === null) {
            // No period filter: fill from the latest existing balance to today
            if (empty($existingBalances)) {
                return $existingBalances;
            }

            // Find the latest month from all existing balances
            $latestMonth = null;
            foreach ($existingBalances as $balance) {
                $balanceMonth = $balance->getMonth();
                if ($latestMonth === null || $balanceMonth > $latestMonth) {
                    $latestMonth = $balanceMonth;
                }
            }

            if ($latestMonth === null || $latestMonth >= $endDate) {
                return $existingBalances;
            }

            $startDate = $latestMonth->modify('+1 month');
        } else {
            // With period filter: use the calculated period
            $startDate = match ($periodFilter) {
                PeriodsEnum::SIX_MONTHS => $now->modify('-6 months')->modify('first day of this month'),
                PeriodsEnum::TWELVE_MONTHS => $now->modify('-12 months')->modify('first day of this month'),
            };
        }

        // Group existing balances by account and month for quick lookup
        $existingByAccountMonth = [];
        foreach ($existingBalances as $balance) {
            $accountId = $balance->getAccount()->getId();
            $monthKey = $balance->getMonth()->format('Y-m');
            $existingByAccountMonth[$accountId][$monthKey] = $balance;
        }

        $completeBalances = $existingBalances;

        // For each account, fill missing months
        foreach ($accountIds as $accountId) {
            $account = $this->accountService->get($accountId);

            // Get the latest known balance for this account
            $latestBalance = $this->monthlyBalanceRepository->findLatestForAccount($account);
            if ($latestBalance === null) {
                // No balance history for this account, skip
                continue;
            }

            // Generate missing months
            $currentMonth = $startDate;
            while ($currentMonth <= $endDate) {
                $monthKey = $currentMonth->format('Y-m');

                // If this month doesn't exist for this account, create a virtual balance
                if (! isset($existingByAccountMonth[$accountId][$monthKey])) {
                    $virtualBalance = $this->createVirtualMonthlyBalance(
                        $account,
                        $currentMonth,
                        $latestBalance->getEndOfMonthBalance()
                    );
                    $completeBalances[] = $virtualBalance;
                }

                $currentMonth = $currentMonth->modify('+1 month');
            }
        }

        return $completeBalances;
    }

    /**
     * Create a virtual MonthlyBalance for visualization purposes.
     */
    private function createVirtualMonthlyBalance(
        Account $account,
        \DateTimeInterface $month,
        float $balance
    ): MonthlyBalance {
        $virtualBalance = new MonthlyBalance();
        $virtualBalance->setAccount($account)
            ->setMonth($month)
            ->setEndOfMonthBalance($balance)
            ->setTransactionCount(0)
        ;

        return $virtualBalance;
    }
}
