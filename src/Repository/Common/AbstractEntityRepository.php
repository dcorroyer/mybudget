<?php

declare(strict_types=1);

namespace App\Repository\Common;

use App\Adapter\PaginationQueryParamsInterface;
use App\Contract\ORMFilterInterface;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

/**
 * @template T of object
 *
 * @template-extends BaseRepository<T>
 */
abstract class AbstractEntityRepository extends BaseRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(
        protected ManagerRegistry $managerRegistry,
        protected PaginatorService $paginatorService,
    ) {
        parent::__construct($managerRegistry, $this->getEntityClass());
    }

    /**
     * @return class-string<T> The entity class name
     */
    abstract public function getEntityClass(): string;

    public function save(object $entity, bool $flush = false): string|int|null
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();

            return $entity->getId();  // @phpstan-ignore-line
        }

        return null;
    }

    public function delete(object $entity, bool $flush = false): string|int
    {
        $id = $entity->getId();  // @phpstan-ignore-line
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }

        return $id;
    }

    public function count(array $criteria = []): int
    {
        return $this->count($criteria);
    }

    public function persist(object $entity): void
    {
        $this->_em->persist($entity);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * @return SlidingPagination<T>
     */
    public function paginate(
        PaginationQueryParamsInterface $paginationQueryParams = null,
        ORMFilterInterface $filter = null,
        Criteria $extraCriteria = null
    ): SlidingPagination {
        $qb = $this->createQueryBuilder('e');

        return $this->paginatorService->paginate($qb, $paginationQueryParams, $filter, $extraCriteria);
    }
}
