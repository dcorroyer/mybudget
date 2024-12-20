<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\BalanceHistory;
use App\Enum\PeriodsEnum;
use App\Shared\Doctrine\Repository\AbstractEntityRepository;
use Carbon\Carbon;

/**
 * @extends AbstractEntityRepository<BalanceHistory>
 */
class BalanceHistoryRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return BalanceHistory::class;
    }

    public function findLatestBalance(Account $account): ?float
    {
        $qb = $this->createQueryBuilder('bh')
            ->select('bh.balanceAfterTransaction')
            ->where('bh.account = :account')
            ->setParameter('account', $account)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        /** @var array{balanceAfterTransaction: float}|null $qb */
        return $qb['balanceAfterTransaction'] ?? null;
    }

    /**
     * @return array<BalanceHistory>
     */
    public function findEntriesFromDate(Account $account, \DateTimeInterface $date): array
    {
        /** @var array<BalanceHistory> */
        return $this->createQueryBuilder('bh')
            ->where('bh.account = :account')
            ->andWhere('bh.date >= :date')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->orderBy('bh.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBalanceBeforeDate(Account $account, \DateTimeInterface $date): ?float
    {
        $qb = $this->createQueryBuilder('bh')
            ->select('bh.balanceAfterTransaction')
            ->where('bh.account = :account')
            ->andWhere('bh.date < :date')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        /** @var array{balanceAfterTransaction: float}|null $qb */
        return $qb['balanceAfterTransaction'] ?? null;
    }

    public function findBalanceAtEndOfMonth(Account $account, string $yearMonth): ?float
    {
        $startDate = new \DateTime($yearMonth . '-01');
        $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('bh')
            ->select('bh.balanceAfterTransaction')
            ->where('bh.account = :account')
            ->andWhere('bh.date <= :endDate')
            ->setParameter('account', $account)
            ->setParameter('endDate', $endDate)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        /** @var array{balanceAfterTransaction: float}|null $qb */
        return $qb['balanceAfterTransaction'] ?? null;
    }

    /**
     * @param array<int>|null $accountIds
     *
     * @return array<BalanceHistory>
     */
    public function findBalancesByAccountsAndByPeriods(?array $accountIds = null, ?PeriodsEnum $period = null): array
    {
        $qb = $this->createQueryBuilder('bh')
            ->select('bh')
            ->orderBy('bh.date', 'DESC')
        ;

        if ($accountIds !== null && \count($accountIds) > 0) {
            $qb->andWhere('bh.account IN (:accountIds)')
                ->setParameter('accountIds', $accountIds)
            ;
        }

        if ($period !== null) {
            $date = Carbon::now();
            $date->modify('-' . $period->value . ' months');
            $date->setTime(0, 0);

            $qb->andWhere('bh.date >= :startDate')
                ->setParameter('startDate', $date)
            ;
        }

        /** @var array<BalanceHistory> */
        return $qb->getQuery()->getResult();
    }
}
