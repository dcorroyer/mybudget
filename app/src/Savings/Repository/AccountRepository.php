<?php

declare(strict_types=1);

namespace App\Savings\Repository;

use App\Core\Repository\AbstractEntityRepository;
use App\Savings\Entity\Account;

/**
 * @extends AbstractEntityRepository<Account>
 */
class AccountRepository extends AbstractEntityRepository
{
    #[\Override]
    public function getEntityClass(): string
    {
        return Account::class;
    }
}
