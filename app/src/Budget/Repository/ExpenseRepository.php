<?php

declare(strict_types=1);

namespace App\Budget\Repository;

use App\Budget\Entity\Expense;
use App\Shared\Repository\Abstract\AbstractEntityRepository;

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
