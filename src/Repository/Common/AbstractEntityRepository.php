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
}
