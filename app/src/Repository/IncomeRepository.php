<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Income;
use App\Shared\Doctrine\Repository\AbstractEntityRepository;

/**
 * @extends AbstractEntityRepository<Income>
 */
class IncomeRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Income::class;
    }
}
