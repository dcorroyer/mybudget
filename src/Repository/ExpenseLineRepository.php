<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExpenseLine;
use App\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<ExpenseLine>
 *
 * @method ExpenseLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseLine[]    findAll()
 * @method ExpenseLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseLineRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return ExpenseLine::class;
    }
}
