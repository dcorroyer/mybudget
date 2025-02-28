<?php

declare(strict_types=1);

namespace App\Savings\Repository;

use App\Savings\Entity\Account;
use App\Savings\Entity\Transaction;
use App\Shared\Repository\Abstract\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Transaction>
 */
class TransactionRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Transaction::class;
    }

    /**
     * @return array<Transaction>
     */
    public function findAllTransactionsFromDate(Account $account, \DateTimeInterface $date): array
    {
        /** @var array<Transaction> */
        return $this->createQueryBuilder('t')
            ->where('t.account = :account')
            ->andWhere('t.date >= :date')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->orderBy('t.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<Transaction>
     */
    public function findAllTransactionsFromDateExcept(
        Account $account,
        \DateTimeInterface $date,
        int $excludedId
    ): array {
        /** @var array<Transaction> */
        return $this->createQueryBuilder('t')
            ->where('t.account = :account')
            ->andWhere('t.date >= :date')
            ->andWhere('t.id != :excludedId')
            ->setParameter('account', $account)
            ->setParameter('date', $date)
            ->setParameter('excludedId', $excludedId)
            ->orderBy('t.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
