<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\BalanceHistory;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

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
            ->select('bh.balance')
            ->where('bh.account = :account')
            ->setParameter('account', $account)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
        ;

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result['balance'] ?? null;
    }

    public function findBalanceAtDate(Account $account, \DateTimeInterface $date): ?float
    {
        $qb = $this->createQueryBuilder('bh')
            ->select('bh.balance')
            ->where('bh.account = :account')
            ->andWhere('bh.date <= :date')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
        ;

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result['balance'] ?? null;
    }

    /**
     * @return array<BalanceHistory>
     */
    public function findEntriesFromDate(Account $account, \DateTimeInterface $date): array
    {
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
        $result = $this->createQueryBuilder('bh')
            ->select('bh.balance')
            ->where('bh.account = :account')
            ->andWhere('bh.date < :date')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->orderBy('bh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result['balance'] ?? null;
    }

    /**
     * @return array<array{date: string, balance: float}>
     */
    public function findMonthlyBalances(Account $account, \DateTimeInterface $startDate): array
    {
        return $this->createQueryBuilder('bh')
            ->select('MAX(bh.date) as date', 'MAX(bh.balance) as balance')
            ->where('bh.account = :account')
            ->andWhere('bh.date >= :startDate')
            ->setParameter('account', $account)
            ->setParameter('startDate', $startDate)
            ->groupBy('YEAR(bh.date)', 'MONTH(bh.date)')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
