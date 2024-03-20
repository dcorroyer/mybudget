<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExpenseCategory;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<ExpenseCategory>
 *
 * @method ExpenseCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseCategory[]    findAll()
 * @method ExpenseCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseCategoryRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return ExpenseCategory::class;
    }
}
