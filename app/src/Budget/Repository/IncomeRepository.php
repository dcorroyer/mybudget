<?php

declare(strict_types=1);

namespace App\Budget\Repository;

use App\Budget\Entity\Income;
use App\Shared\Repository\Abstract\AbstractEntityRepository;

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
