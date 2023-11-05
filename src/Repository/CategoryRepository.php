<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return Category::class;
    }
}
