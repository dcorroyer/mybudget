<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Account\Response\AccountPartialResponse;
use App\Dto\BalanceHistory\Http\BalanceHistoryFilterQuery;
use App\Dto\BalanceHistory\Response\BalanceHistoryResponse;
use App\Dto\BalanceHistory\Response\BalanceResponse;
use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Entity\Transaction;
use App\Enum\TransactionTypesEnum;
use App\Repository\BalanceHistoryRepository;
use App\Repository\TransactionRepository;

class BalanceHistoryService
{
    public function __construct(
        private readonly BalanceHistoryRepository $balanceHistoryRepository,
        private readonly TransactionRepository $transactionRepository,
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
        $this->recalculateBalanceHistory($transaction->getAccount(), $transaction->getDate());
    }

    public function deleteBalanceHistoryEntry(Transaction $transaction): void
    {
        $this->recalculateBalanceHistory(
            $transaction->getAccount(),
            $transaction->getDate(),
            $transaction->getId()
        );
    }

    public function getMonthlyBalanceHistory(?BalanceHistoryFilterQuery $filter = null): BalanceHistoryResponse
    {
        // DEBUT récupération des comptes
        $accountsInfo = [];

        if ($filter?->getAccountIds() !== null) {
            foreach ($filter?->getAccountIds() as $accountId) {
                $account = $this->accountService->get($accountId);

                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
            }

            $accounts = $filter?->getAccountIds();
        } else {
            $accounts = $this->accountService->list();

            foreach ($accounts as $account) {
                $accountsInfo[] = new AccountPartialResponse($account->getId(), $account->getName());
            }
        }
        // FIN récupération des comptes

        // DEBUT récupération manipulation et tri des balances
        $balanceHistories = $this->balanceHistoryRepository->findBalancesByAccounts($accounts, $filter?->period);

        $monthlyBalances = [];
        $dates = [];

        foreach ($balanceHistories as $history) {
            $period = $history->getDate()->format('Y-m');
            $dates[$period] = true;
        }

        ksort($dates);
        $dates = array_keys($dates);

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
                (new \DateTime($yearMonth . '-01'))->format('F Y'),
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
            ? $this->transactionRepository->findAllTransactionsFromDateExcept(
                $account,
                $fromDate,
                $excludedTransactionId
            )
            : $this->transactionRepository->findAllTransactionsFromDate($account, $fromDate);

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
