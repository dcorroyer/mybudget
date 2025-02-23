<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Expense;
use App\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Expense>
 */
class ExpenseRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Expense::class;
    }
}
