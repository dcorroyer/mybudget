<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Budget;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Budget>
 *
 * @method Budget|null find($id, $lockMode = null, $lockVersion = null)
 * @method Budget|null findOneBy(array $criteria, array $orderBy = null)
 * @method Budget[]    findAll()
 * @method Budget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return Budget::class;
    }
}
