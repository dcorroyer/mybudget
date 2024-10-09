<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExpenseCategory;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<ExpenseCategory>
 */
class ExpenseCategoryRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return ExpenseCategory::class;
    }
}
