<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExpenseLineCategory;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<ExpenseLineCategory>
 *
 * @method ExpenseLineCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseLineCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseLineCategory[]    findAll()
 * @method ExpenseLineCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseLineCategoryRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return ExpenseLineCategory::class;
    }
}
