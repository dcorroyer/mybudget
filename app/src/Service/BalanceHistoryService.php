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

        $this->updateBalanceHistory($account, $transaction->getDate());
    }

    public function updateBalanceHistoryEntry(Transaction $transaction): void
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        $this->updateBalanceHistory($account, $transaction->getDate());
    }

    public function deleteBalanceHistoryEntry(Transaction $transaction): void
    {
        /** @var Account $account */
        $account = $transaction->getAccount();

        $this->updateBalanceHistory($account, $transaction->getDate(), $transaction->getId());
    }

    /**
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

        $accounts = $accountIds;

        $balanceHistories = $this->balanceHistoryRepository->findBalancesByAccounts($accounts, $periodFilter);
        $monthlyBalances = [];
        $uniquePeriods = [];

        foreach ($balanceHistories as $history) {
            $period = $history->getDate()->format('Y-m');
            $uniquePeriods[$period] = true;
        }

        ksort($uniquePeriods);
        $dates = array_keys($uniquePeriods);

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

    private function updateBalanceHistory(
        Account $account,
        \DateTimeInterface $fromDate,
        ?int $excludedTransactionId = null
    ): void {
        $entriesToDelete = $this->balanceHistoryRepository->findEntriesFromDate($account, $fromDate);
        foreach ($entriesToDelete as $entry) {
            $this->balanceHistoryRepository->delete($entry);
        }

        $initialBalance = $this->balanceHistoryRepository->findBalanceBeforeDate($account, $fromDate) ?? 0.0;

        $transactions = $excludedTransactionId !== null
            ? $this->transactionService->getAllTransactionsFromDateExcept($account, $fromDate, $excludedTransactionId)
            : $this->transactionService->getAllTransactionsFromDate($account, $fromDate);

        $currentBalance = $initialBalance;

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $previousBalance = $currentBalance;
            $amount = $transaction->getAmount();

            $currentBalance += $transaction->getType() === TransactionTypesEnum::CREDIT ? $amount : -$amount;

            $balanceHistory = (new BalanceHistory())
                ->setDate($transaction->getDate())
                ->setBalanceBeforeTransaction($previousBalance)
                ->setBalanceAfterTransaction($currentBalance)
                ->setAccount($account)
                ->setTransaction($transaction)
            ;

            $this->balanceHistoryRepository->save($balanceHistory);
        }

        $this->balanceHistoryRepository->flush();
    }
}
