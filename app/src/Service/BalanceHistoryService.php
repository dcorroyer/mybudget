<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BalanceHistory\Http\BalanceHistoryFilterQuery;
use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Entity\Transaction;
use App\Enum\ErrorMessagesEnum;
use App\Enum\PeriodsEnum;
use App\Enum\TransactionTypesEnum;
use App\Repository\BalanceHistoryRepository;
use App\Repository\TransactionRepository;
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
        $account = $transaction->getAccount();
        $currentBalance = $this->getLatestBalance($account) ?? 0.0;
        $newBalance = $currentBalance + $this->calculateBalanceImpact(
            $transaction->getAmount(),
            $transaction->getType()
        );

        $balanceHistory = (new BalanceHistory())
            ->setDate($transaction->getDate())
            ->setBalance($newBalance)
            ->setAccount($account)
            ->setTransaction($transaction)
        ;

        $this->balanceHistoryRepository->save($balanceHistory, true);
    }

    public function getLatestBalance(Account $account): ?float
    {
        return $this->balanceHistoryRepository->findLatestBalance($account);
    }

    public function getBalanceAtDate(Account $account, \DateTimeInterface $date): ?float
    {
        return $this->balanceHistoryRepository->findBalanceAtDate($account, $date);
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

    /**
     * @return array<array{date: string, balance: float}>
     */
    public function getMonthlyBalances(?BalanceHistoryFilterQuery $filter = null): array
    {
        $startDate = match ($filter?->period) {
            PeriodsEnum::THREE_MONTHS => new \DateTimeImmutable('first day of -3 months'),
            PeriodsEnum::SIX_MONTHS => new \DateTimeImmutable('first day of -6 months'),
            PeriodsEnum::TWELVE_MONTHS => new \DateTimeImmutable('first day of -12 months'),
            default => new \DateTimeImmutable('1970-01-01'),
        };

        if (empty($filter->accountIds)) {
            $accounts = $this->accountService->list();
        } else {
            $accounts = array_map(
                function (int $accountId) {
                    $account = $this->accountService->get($accountId);

                    if (! $this->authorizationChecker->isGranted('view', $account)) {
                        throw new AccessDeniedHttpException(ErrorMessagesEnum::ACCESS_DENIED->value);
                    }

                    return $account;
                },

                $filter->accountIds
            );
        }

        $balancesByMonth = [];

        foreach ($accounts as $account) {
            $monthlyBalances = $this->balanceHistoryRepository->findMonthlyBalances($account, $startDate);

            foreach ($monthlyBalances as $balance) {
                $month = (new \DateTimeImmutable($balance['date']))->format('Y-m');

                if (! isset($balancesByMonth[$month])) {
                    $balancesByMonth[$month] = 0;
                }

                $balancesByMonth[$month] += $balance['balance'];
            }
        }

        ksort($balancesByMonth);

        return array_map(
            static fn (string $month, float $balance) => [
                'date' => $month,
                'balance' => $balance,
            ],
            array_keys($balancesByMonth),
            array_values($balancesByMonth)
        );
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
            $runningBalance += $this->calculateBalanceImpact($transaction->getAmount(), $transaction->getType());

            $balanceHistory = (new BalanceHistory())
                ->setDate($transaction->getDate())
                ->setBalance($runningBalance)
                ->setAccount($account)
                ->setTransaction($transaction)
            ;

            $this->balanceHistoryRepository->save($balanceHistory);
        }

        $this->balanceHistoryRepository->flush();
    }

    private function calculateBalanceImpact(float $amount, TransactionTypesEnum $type): float
    {
        return $type === TransactionTypesEnum::CREDIT ? $amount : -$amount;
    }
}
