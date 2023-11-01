<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExpenseLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseLine>
 *
 * @method ExpenseLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseLine[]    findAll()
 * @method ExpenseLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseLine::class);
    }
}
