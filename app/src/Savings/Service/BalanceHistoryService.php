<?php

declare(strict_types=1);

namespace App\Savings\Service;

use App\Savings\Dto\Account\Response\AccountPartialResponse;
use App\Savings\Dto\BalanceHistory\Response\BalanceHistoryResponse;
use App\Savings\Dto\BalanceHistory\Response\BalanceResponse;
use App\Savings\Entity\Account;
use App\Savings\Entity\BalanceHistory;
use App\Savings\Entity\Transaction;
use App\Savings\Repository\BalanceHistoryRepository;
use App\Shared\Enum\PeriodsEnum;
use App\Shared\Enum\TransactionTypesEnum;
use Carbon\Carbon;
use loophp\collection\Collection;
use loophp\collection\Contract\Operation\Sortable;

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

        $balanceHistories = $this->balanceHistoryRepository->findBalancesByAccountsAndByPeriods(
            $accounts,
            $periodFilter
        );

        $dates = Collection::fromIterable($balanceHistories)
            ->map(static fn (BalanceHistory $history) => $history->getDate()->format('Y-m'))
            ->distinct()
            ->sort()
            ->all()
        ;

        $monthlyBalances = [];

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

        $balancesInfo = Collection::fromIterable($monthlyBalances)
            ->map(static fn (float $balance, string $yearMonth) => new BalanceResponse(
                $yearMonth,
                // @phpstan-ignore-next-line
                Carbon::parse($yearMonth . '-01')->format('Y-m'),
                $balance
            ))
            ->sort(
                Sortable::BY_VALUES,
                static fn (BalanceResponse $a, BalanceResponse $b): int => $a->date <=> $b->date
            )
            ->all()
        ;

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
