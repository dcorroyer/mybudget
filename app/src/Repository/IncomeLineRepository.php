<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\IncomeLine;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<IncomeLine>
 *
 * @method IncomeLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncomeLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncomeLine[]    findAll()
 * @method IncomeLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeLineRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return IncomeLine::class;
    }
}
