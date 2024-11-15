<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Account\Response\AccountPartialResponse;
use App\Dto\BalanceHistory\Response\BalanceHistoryResponse;
use App\Dto\BalanceHistory\Response\BalanceResponse;
use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Entity\Transaction;
use App\Enum\PeriodsEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\BalanceHistoryRepository;
use Carbon\Carbon;

class BalanceHistoryService
{
    public function __construct(
        private readonly BalanceHistoryRepository $balanceHistoryRepository,
        private readonly TransactionService $transactionService,
        private readonly AccountService $accountService,
    ) {
    }

    public function createBalanceHistoryEntry(Transaction $transaction): void
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        $balanceBeforeTransaction = $this->getLatestBalance($account) ?? 0.0;
        $balanceAfterTransaction = $balanceBeforeTransaction + $this->calculateBalanceImpact(
            $transaction->getAmount(),
            $transaction->getType()
        );

        $balanceHistory = (new BalanceHistory())
            ->setDate($transaction->getDate())
            ->setBalanceBeforeTransaction($balanceBeforeTransaction)
            ->setBalanceAfterTransaction($balanceAfterTransaction)
            ->setAccount($account)
            ->setTransaction($transaction)
        ;

        $this->balanceHistoryRepository->save($balanceHistory, true);
    }

    public function getLatestBalance(Account $account): ?float
    {
        return $this->balanceHistoryRepository->findLatestBalance($account);
    }

    public function updateBalanceHistoryEntry(Transaction $transaction): void
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        $this->recalculateBalanceHistory($account, $transaction->getDate());
    }

    public function deleteBalanceHistoryEntry(Transaction $transaction): void
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        $this->recalculateBalanceHistory($account, $transaction->getDate(), $transaction->getId());
    }

    /**
     * @param array<int>|null $accountIds
     */
    public function getMonthlyBalanceHistory(
        ?array $accountIds = null,
        ?PeriodsEnum $periodFilter = null
    ): BalanceHistoryResponse {
        // DEBUT récupération des comptes
        $accountsInfo = [];

        if ($accountIds !== null) {
            foreach ($accountIds as $accountId) {
                $account = $this->accountService->get($accountId);

                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
            }
        } else {
            $userAccounts = $this->accountService->list();

            foreach ($userAccounts as $account) {
                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
                $accountIds[] = $account->getId();
            }
        }

        $accounts = $accountIds ?? [];
        // FIN récupération des comptes

        // DEBUT récupération manipulation et tri des balances
        $balanceHistories = $this->balanceHistoryRepository->findBalancesByAccounts($accounts, $periodFilter);

        $monthlyBalances = [];
        $dateMap = [];

        foreach ($balanceHistories as $history) {
            $period = $history->getDate()->format('Y-m');
            $dateMap[$period] = true;
        }

        ksort($dateMap);
        $dates = array_keys($dateMap);

        foreach ($accounts as $accountId) {
            $account = $this->accountService->get($accountId);
            $lastKnownBalance = null;

            foreach ($dates as $period) {
                if (! isset($monthlyBalances[$period])) {
                    $monthlyBalances[$period] = 0;
                }

                $endOfMonthBalance = $this->balanceHistoryRepository->findBalanceAtEndOfMonth($account, $period);

                if ($endOfMonthBalance !== null) {
                    $lastKnownBalance = $endOfMonthBalance;
                    $monthlyBalances[$period] += $endOfMonthBalance;
                } elseif ($lastKnownBalance !== null) {
                    $monthlyBalances[$period] += $lastKnownBalance;
                }
            }
        }

        ksort($monthlyBalances);
        // FIN récupération manipulation et tri des balances

        $balancesInfo = [];

        foreach ($monthlyBalances as $yearMonth => $balance) {
            $balancesInfo[] = new BalanceResponse(
                $yearMonth,
                Carbon::parse($yearMonth . '-01')->format('Y-m'),
                $balance
            );
        }

        return new BalanceHistoryResponse($accountsInfo, $balancesInfo);
    }

    private function recalculateBalanceHistory(
        Account $account,
        \DateTimeInterface $fromDate,
        ?int $excludedTransactionId = null
    ): void {
        $balanceHistoryEntriesToDelete = $this->balanceHistoryRepository->findEntriesFromDate($account, $fromDate);
        $previousBalance = $this->balanceHistoryRepository->findBalanceBeforeDate($account, $fromDate) ?? 0.0;

        foreach ($balanceHistoryEntriesToDelete as $historyEntry) {
            $this->balanceHistoryRepository->delete($historyEntry);
        }

        $transactionsToRecalculate = $excludedTransactionId !== null
            ? $this->transactionService->getAllTransactionsFromDateExcept(
                $account,
                $fromDate,
                $excludedTransactionId
            )
            : $this->transactionService->getAllTransactionsFromDate($account, $fromDate);

        $runningBalance = $previousBalance;

        foreach ($transactionsToRecalculate as $transaction) {
            $balanceBeforeTransaction = $runningBalance;
            $balanceAfterTransaction = $runningBalance + $this->calculateBalanceImpact(
                $transaction->getAmount(),
                $transaction->getType()
            );

            $balanceHistory = (new BalanceHistory())
                ->setDate($transaction->getDate())
                ->setBalanceBeforeTransaction($balanceBeforeTransaction)
                ->setBalanceAfterTransaction($balanceAfterTransaction)
                ->setAccount($account)
                ->setTransaction($transaction)
            ;

            $this->balanceHistoryRepository->save($balanceHistory);
            $runningBalance = $balanceAfterTransaction;
        }

        $this->balanceHistoryRepository->flush();
    }

    private function calculateBalanceImpact(float $amount, TransactionTypesEnum $type): float
    {
        return $type === TransactionTypesEnum::CREDIT ? $amount : -$amount;
    }
}
