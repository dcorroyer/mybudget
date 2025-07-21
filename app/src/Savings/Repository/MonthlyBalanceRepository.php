<?php

declare(strict_types=1);

namespace App\Savings\Repository;

use App\Savings\Entity\Account;
use App\Savings\Entity\MonthlyBalance;
use App\Savings\Enum\PeriodsEnum;
use App\Shared\Repository\Abstract\AbstractEntityRepository;
use Carbon\Carbon;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends AbstractEntityRepository<MonthlyBalance>
 */
class MonthlyBalanceRepository extends AbstractEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator,
    ) {
        parent::__construct($registry, $paginator);
    }

    #[\Override]
    public function getEntityClass(): string
    {
        return MonthlyBalance::class;
    }

    /**
     * Find MonthlyBalance for a specific account and month.
     */
    public function findByAccountAndMonth(Account $account, \DateTimeInterface $month): ?MonthlyBalance
    {
        // Ensure we search by the first day of the month
        $firstDayOfMonth = (new \DateTimeImmutable($month->format('Y-m-01')))->setTime(0, 0, 0);

        /** @var MonthlyBalance|null */
        return $this->createQueryBuilder('mb')
            ->andWhere('mb.account = :account')
            ->andWhere('mb.month = :month')
            ->setParameter('account', $account)
            ->setParameter('month', $firstDayOfMonth)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Find all MonthlyBalance for accounts within a period.
     *
     * @param array<int> $accountIds
     *
     * @return array<MonthlyBalance>
     */
    public function findByAccountsAndPeriod(array $accountIds, ?PeriodsEnum $period = null): array
    {
        $qb = $this->createQueryBuilder('mb')
            ->leftJoin('mb.account', 'a')
            ->andWhere('a.id IN (:accountIds)')
            ->setParameter('accountIds', $accountIds)
            ->orderBy('mb.month', 'ASC')
        ;

        if ($period !== null) {
            $dateFilter = $this->getDateFilterForPeriod($period);
            $qb->andWhere('mb.month >= :dateFrom')
                ->setParameter('dateFrom', $dateFilter)
            ;
        }

        /** @var array<MonthlyBalance> */
        return $qb->getQuery()->getResult();
    }

    /**
     * Find the latest MonthlyBalance before a specific month.
     */
    public function findLatestBeforeMonth(Account $account, \DateTimeInterface $month): ?MonthlyBalance
    {
        $firstDayOfMonth = (new \DateTimeImmutable($month->format('Y-m-01')))->setTime(0, 0, 0);

        /** @var MonthlyBalance|null */
        return $this->createQueryBuilder('mb')
            ->andWhere('mb.account = :account')
            ->andWhere('mb.month < :month')
            ->setParameter('account', $account)
            ->setParameter('month', $firstDayOfMonth)
            ->orderBy('mb.month', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Find all MonthlyBalance for an account from a specific month onwards.
     *
     * @return array<MonthlyBalance>
     */
    public function findFromMonthOnwards(Account $account, \DateTimeInterface $fromMonth): array
    {
        $firstDayOfMonth = (new \DateTimeImmutable($fromMonth->format('Y-m-01')))->setTime(0, 0, 0);

        /** @var array<MonthlyBalance> */
        return $this->createQueryBuilder('mb')
            ->andWhere('mb.account = :account')
            ->andWhere('mb.month >= :month')
            ->setParameter('account', $account)
            ->setParameter('month', $firstDayOfMonth)
            ->orderBy('mb.month', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Delete all MonthlyBalance for an account from a specific month onwards.
     */
    public function deleteFromMonthOnwards(Account $account, \DateTimeInterface $fromMonth): int
    {
        $firstDayOfMonth = (new \DateTimeImmutable($fromMonth->format('Y-m-01')))->setTime(0, 0, 0);

        /** @var int */
        return $this->createQueryBuilder('mb')
            ->delete()
            ->andWhere('mb.account = :account')
            ->andWhere('mb.month >= :month')
            ->setParameter('account', $account)
            ->setParameter('month', $firstDayOfMonth)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Get count of MonthlyBalance entries for an account.
     */
    public function countByAccount(Account $account): int
    {
        return (int) $this->createQueryBuilder('mb')
            ->select('COUNT(mb.id)')
            ->andWhere('mb.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Find the latest MonthlyBalance for an account.
     */
    public function findLatestForAccount(Account $account): ?MonthlyBalance
    {
        /** @var MonthlyBalance|null */
        return $this->createQueryBuilder('mb')
            ->andWhere('mb.account = :account')
            ->setParameter('account', $account)
            ->orderBy('mb.month', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function getDateFilterForPeriod(PeriodsEnum $period): \DateTimeImmutable
    {
        $now = Carbon::now()->toDateTimeImmutable();

        return match ($period) {
            PeriodsEnum::SIX_MONTHS => $now->modify('-6 months')->modify('first day of this month'),
            PeriodsEnum::TWELVE_MONTHS => $now->modify('-12 months')->modify('first day of this month'),
        };
    }
}
