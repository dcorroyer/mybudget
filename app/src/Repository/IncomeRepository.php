<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Income;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Income>
 *
 * @method Income|null find($id, $lockMode = null, $lockVersion = null)
 * @method Income|null findOneBy(array $criteria, array $orderBy = null)
 * @method Income[]    findAll()
 * @method Income[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return Income::class;
    }
}
