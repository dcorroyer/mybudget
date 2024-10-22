<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use My\RestBundle\Repository\Common\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Transaction>
 */
class TransactionRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Transaction::class;
    }
}
