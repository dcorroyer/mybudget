<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ChargeLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChargeLine>
 *
 * @method ChargeLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChargeLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChargeLine[]    findAll()
 * @method ChargeLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargeLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChargeLine::class);
    }

    //    /**
    //     * @return ChargeLine[] Returns an array of ChargeLine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ChargeLine
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
