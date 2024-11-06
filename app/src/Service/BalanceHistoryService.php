<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BalanceHistory\Http\BalanceHistoryFilterQuery;
use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\BalanceHistoryRepository;
use App\Repository\TransactionRepository;
use App\Security\Voter\AccountVoter;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BalanceHistoryService
{
    public function __construct(
        private readonly BalanceHistoryRepository $balanceHistoryRepository,
        private readonly TransactionRepository $transactionRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
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

    public function getMonthlyBalanceHistory(?BalanceHistoryFilterQuery $filter = null): array
    {
        $accountsInfo = [];

        if ($filter?->getAccountIds() !== null) {
            foreach ($filter?->getAccountIds() as $accountId) {
                $account = $this->accountService->get($accountId);

                if (! $this->authorizationChecker->isGranted(AccountVoter::VIEW, $account)) {
                    throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
                }

                $accountsInfo[] = [
                    'id' => $account->getId(),
                    'name' => $account->getName(),
                ];
            }

            $accounts = $filter?->getAccountIds();
        } else {
            $accounts = $this->accountService->list();
            foreach ($accounts as $account) {
                $accountsInfo[] = [
                    'id' => $account->getId(),
                    'name' => $account->getName(),
                ];
            }
        }

        $balanceHistories = $this->balanceHistoryRepository->findBalancesByAccounts($accounts, $filter?->period);

        $monthlyBalances = [];
        $processedMonths = [];

        foreach ($balanceHistories as $history) {
            $yearMonth = $history->getDate()->format('Y-m');

            if (! isset($processedMonths[$yearMonth])) {
                $processedMonths[$yearMonth] = true;

                $endOfMonthBalance = $this->balanceHistoryRepository->findBalanceAtEndOfMonth(
                    $history->getAccount(),
                    $yearMonth
                );

                if ($endOfMonthBalance !== null) {
                    $monthlyBalances[$yearMonth] = $endOfMonthBalance;
                }
            }
        }

        ksort($monthlyBalances);

        return [
            'accounts' => $accountsInfo,
            'balances' => array_map(
                static fn ($yearMonth, $balance) => [
                    'date' => $yearMonth,
                    'formattedDate' => (new \DateTime($yearMonth . '-01'))->format('F Y'),
                    'balance' => (float) $balance,
                ],
                array_keys($monthlyBalances),
                array_values($monthlyBalances)
            ),
        ];
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
