<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Repository\AbstractEntityRepository;
use App\Entity\Income;

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
