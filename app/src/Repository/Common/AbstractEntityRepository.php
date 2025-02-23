<?php

declare(strict_types=1);

namespace App\Repository\Common;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of object
 *
 * @template-extends BaseRepository<T>
 */
abstract class AbstractEntityRepository extends BaseRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(
        protected ManagerRegistry $managerRegistry,
    ) {
        parent::__construct($managerRegistry, $this->getEntityClass());
    }

    /**
     * @return class-string<T> The entity class name
     */
    abstract public function getEntityClass(): string;

    public function save(object $entity, bool $flush = false): string|int|null
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();

            return $entity->getId();  // @phpstan-ignore-line
        }

        return null;
    }

    public function delete(object $entity, bool $flush = false): string|int
    {
        $id = $entity->getId();  // @phpstan-ignore-line
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $id;
    }

    public function persist(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
