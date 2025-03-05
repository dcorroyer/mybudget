<?php

declare(strict_types=1);

namespace App\Savings\Repository;

use App\Savings\Entity\Account;
use App\Shared\Repository\Abstract\AbstractEntityRepository;

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
