<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository\AbstractEntityRepository;
use App\Entity\Expense;

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
