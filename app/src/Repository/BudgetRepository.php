<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Budget;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

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
}
