<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Budget;
use App\Shared\Doctrine\Repository\AbstractEntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends AbstractEntityRepository<Budget>
 */
class BudgetRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Budget::class;
    }

    public function findLatestByUser(?UserInterface $user): ?Budget
    {
        // @phpstan-ignore-next-line
        return $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
