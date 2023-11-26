<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tracking;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Tracking>
 *
 * @method Tracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tracking[]    findAll()
 * @method Tracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingRepository extends AbstractEntityRepository
{
    public function getEntityClass(): string
    {
        return Tracking::class;
    }
}
